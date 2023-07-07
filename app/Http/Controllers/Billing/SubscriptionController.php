<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Coupon;
use App\Models\Plan;
use App\Models\User;
use App\Services\StripeService;
use App\Traits\StripeBilling;
use App\Traits\CompanyAuthorization;
use App\Models\SubscriptionItem;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\Price as StripePrice;
use Stripe\Stripe;
use Stripe\StripeClient;
use Validator;

class SubscriptionController extends Controller
{
    use CompanyAuthorization;
    use StripeBilling;

    /**
     * Show the Current active Subscription detail.
     *
     * @param  Request  $request
     * @return Response
     */
    public function get(Request $request)
    {
        $company = $request->user()->company;
        $plans = Plan::whereNull('company_id')->get();
        $endsAt = null;
        $activePlan = $company->activePlan();
        $subscription = $company->subscription('default');
        $free = false;
        if (empty($activePlan)) {
            $activePlan = freePlan();
            $free = true;
        } elseif ($subscription->onGracePeriod()) {
            $endsAt = $subscription->ends_at->diffForHumans();
        }
        $activeSubscriptionDetails = [];
        if ($subscription) {
            $activeSubscriptionDetails['plan_price'] = $subscription->plan_price;
            $activeSubscriptionDetails['amount_charged'] = $subscription->amount_charged;
            $activeSubscriptionDetails['coupon_code'] = $subscription->coupon_code;
            $activeSubscriptionDetails['discount_text'] = '';
            if ($subscription->coupon_code) {
                $coupon = Coupon::where('coupon_code', $subscription->coupon_code)->first();
                if ($activePlan && $activePlan->interval == 2) {
                    $activeSubscriptionDetails['discount_text'] = $coupon ? $coupon->description_text_plan_yearly : '';
                } else {
                    $activeSubscriptionDetails['discount_text'] = $coupon ? $coupon->description_text_plan : '';
                }
            }
        }
        if (!empty($activePlan) && !$free) {
            $planExist = false;
            foreach ($plans as $plan) {
                if ($plan->id === $activePlan->id) {
                    $planExist = true;
                    break;
                }
            }
            if (!$planExist) {
                $plans->prepend($activePlan);
            }
        }

        if ($subscription && $subscription->hasIncompletePayment()) {
            $incompletePaymentUrl = route('cashier.payment', [$subscription->latestPayment()->id, 'redirect' => env('APP_URL') . '/subscription/plans']);
        } else {
            $incompletePaymentUrl = '';
        }

        $activeSubscription = $subscription && !$subscription->cancelled();
        $cardLastFour = $company->pm_last_four;
        $popularPlanType = json_decode(env('POPULAR_PLANS'));

        $companyCustomPlan = null;
        // in case of user's has custom plan don't show free plan.
        if ($company->plan && $company->plan == $activePlan) {
            $activePlan = $company->plan->basePlan ?? $activePlan;
            $plans = $plans->where('type', '!=', 'free')->whereNull('base_plan')->sortBy('id')->values();

            $customPlan = $company->customPlan();
            if ($customPlan) {
                $activePlan = $customPlan ?? $activePlan;
                $plans->prepend($customPlan);
            }
        } elseif ($company->plan && $company->plan != $activePlan) {
            $plans->prepend($company->plan);
        }
        //dd($plans);

        $periodicDiscounts = $company->periodicDiscounts;
        $periodicDiscount = null;
        $periodicDiscountAvailable = false;
        $unlockProgramCouponCode = Coupon::where('coupon_code', env('UNLOCK_PROGRAM_COUPON_CODE'))->first();

        if ($unlockProgramCouponCode && $company->activePlan->id == env('UNLOCK_PROGRAM_SOURCE_PLAN_ID')) {
            $periodicDiscount = $company->periodicDiscounts()->where('coupon_id', $unlockProgramCouponCode->id)->first();
            if ($periodicDiscount) {
                $periodicDiscountAvailable = Carbon::now()->lessThanOrEqualTo($periodicDiscount->validate_upto);
            } else {
                $periodicDiscountAvailable = true;
            }
        }
        return Inertia::render('Stripe/Pricing', compact('activePlan', 'plans', 'endsAt', 'cardLastFour', 'incompletePaymentUrl', 'popularPlanType', 'activeSubscription', 'companyCustomPlan', 'activeSubscriptionDetails', 'periodicDiscountAvailable', 'periodicDiscount', 'unlockProgramCouponCode'));
    }

    public function update(Request $request)
    {
        $companyUsed = $this->getComapnyAuth()['companyUsed'];
        $company = $request->user()->company;

        $input = $request->all();
        $isCustomPlan = $input['is_custom_plan'] ?? false;

        if ($isCustomPlan) {
            $features = $input['features'];
            $planPrice = getPlanPrice($features);

            $features = collect($input['features']);
            $planDescription = $features->flatMap(function ($feature) {
                return [
                    $feature['key'] => $feature['value'] ?? false,
                ];
            });

            if (!$planPrice) {
                throw new \Exception('Invalid selection for plan customizer.');
            }
            $planPrice = ($input['interval'] == 2) ? round((($planPrice * 12) - ($planPrice * 2)), 2) : $planPrice;
            $planId = Str::slug(('xme-' . $input['plan_name'] . ' ' . rand(1000, 9999)), '-');
            $plan = new Plan([
                'type' => $planId,
                'name' => $input['plan_name'],
                'description' => $planDescription,
                'price' => $planPrice,
                'interval' => $input['interval'],
            ]);
            $companyPlan = $company->plan()->save($plan);

            $stripePrice = $this->createPrice([
                'currency' => env('CASHIER_CURRENCY'),
                'price' => $planPrice,
                'interval' => $input['interval'] == 1 ? 'month' : 'year',
                'stripe_product_id' => env('STRIPE_CUSTOM_PROD_ID'),
            ]);

            $companyPlan->stripe_plan_id = $stripePrice->id;
            $companyPlan->save();
            $input['plan'] = $planId;
        }

        $this->validator($input)->validate();

        $activePlan = $company->activePlan() ?? freePlan();
        $plan = Plan::where('type', '=', $input['plan'])->first();

        // if the user is going to downgrade the plan.
        if ($activePlan->price > $plan->price) {
            $planDesc = $plan->description;

            foreach ($planDesc as $key => $value) {
                if (array_key_exists($key, $companyUsed) && array_key_exists($key, $planDesc)) {
                    $planCanDowngrade[$key] = $planDesc[$key] - $companyUsed[$key];
                }
            }
            $planCanDowngrade = array_filter($planCanDowngrade, function ($i) {
                return $i < 0;
            });

            if (count($planCanDowngrade)) {
                $keyReason = Str::ucfirst(Str::replace('_', ' ', array_key_first($planCanDowngrade)));
                abort(403, 'You can\'t downgrade the plan. You have to delete or adjust ' . $keyReason . ' for downgrade the plan.');
            }
        }
        if ($activePlan->type != "free") {
            $whereData = [
                ['company_id', $company->id],
                ['addons_description', '!=', null],
                ['stripe_product_id', '!=', null],
                ['stripe_addon_plan_id', '!=', null],
            ];
            $addon = Plan::where($whereData)->latest()->first();
            if ($addon) {
                $newBasePlan = $plan;
                Stripe::setApiKey(config('stripe.secret'));
                $planId = Str::slug(('xme-' . $input['plan_name'] . ' ' . rand(1000, 9999)), '-');
                $planPrice = StripePrice::retrieve($addon->stripe_addon_plan_id, ['product' => $addon->stripe_product_id])->unit_amount;
                $newPlanPrice = $planPrice;
                $stripe_price_id = $addon->stripe_addon_plan_id;
                if ($addon->interval != $input['interval']) {
                    $newPlanPrice = $input['interval'] == 2 ? round(($planPrice * 10), 2) : $planPrice / 10;
                    $stripe_price_id = $this->createPrice([
                        'currency' => env('CASHIER_CURRENCY'),
                        'price' => $newPlanPrice / 100,
                        'interval' => $input['interval'] == 1 ? 'month' : 'year',
                        'stripe_product_id' => $addon->stripe_product_id,
                    ])->id;
                }
                $plan = Plan::create([
                    'type' => $planId,
                    'name' => $planId,
                    'description' => is_array($newBasePlan->description) ? json_encode($newBasePlan->description) : $newBasePlan->description,
                    'addons_description' => is_array($addon->addons_description) ? json_encode($addon->addons_description) : $addon->addons_description,
                    'stripe_plan_id' => $newBasePlan->stripe_plan_id,
                    'stripe_addon_plan_id' => $stripe_price_id,
                    'stripe_product_id' => $addon->stripe_product_id,
                    'price' =>  $newBasePlan->price + $newPlanPrice / 100,
                    'interval' => $input['interval'],
                    'base_plan_id' => $newBasePlan->is_custom_plan ? $newBasePlan->base_plan_id : $newBasePlan->id
                ]);

                $companyPlan = $company->plan()->save($plan);
                $input['plan'] = $planId;
            }
        }

        return $this->updateSubscription($request, $company, $plan);
    }

    public function subscribeToAddon(Request $request)
    {
        $request->validate([
            'features' => 'required|array',
            'stripePriceOD' => 'required',
        ]);
        $input = $request->all();
        $metadata = ['price_id' => $input['stripePriceOD']];
        if ($input['stripePriceOD'] == 'removed') {
            $input['stripePriceOD'] = null;
        }
        $company = $request->user()->company;
        $activePlan = $company->activePlan();
        $basePlan = optional($company->activePlan())->basePlan;
        $metadata['activePlan'] = $activePlan->id;
        $flatFeatures = collect($request->flatFeatures)->keyBy('key');

        $features = collect($input['features']);
        $planDescription = $features->flatMap(function ($feature) {
            return [
                $feature['key'] => $feature['value'] ?? false,
            ];
        });

        $metadata['is_custom'] = $activePlan->is_custom_plan;
        $metadata['old_description'] = $activePlan->addons_description;
        // User is already subscribed to a custom plan
        if ($activePlan->is_custom_plan) {
            // In case of downgrade, populate new value to patch existing.
            $planDescription = $planDescription->map(function ($item, $key) use ($activePlan, $basePlan, $features) {
                if ($features->keyBy('key')->toArray()[$key]['type'] != 'boolean') {
                    return ($activePlan->description[$key] + $item) - $basePlan->description[$key];
                }
                return $item;
            });

            $customPricing = $features->map(function ($feature, $key) use ($planDescription) {
                if ($planDescription[$feature['key']] > 0) {
                    return array_merge(
                        $feature,
                        ['value' => $planDescription[$feature['key']]]
                    );
                }
            })->toArray();

            $customPricing = array_filter($customPricing);


            foreach (json_decode($activePlan->addons_description, true) ?? [] as $key => $value) {
                // Populate existing addons for recalculation, except one that is modified
                if (isset($basePlan->description[$key]) && is_int($basePlan->description[$key])) {
                    if (array_search($key, array_column($input['features'], 'key')) === false && $activePlan->description[$key] - $basePlan->description[$key] > 0) {
                        $customPricing[] = array_merge(
                            $flatFeatures[$key],
                            ['value' => $activePlan->description[$key] - $basePlan->description[$key]]
                        );
                    }
                } else {
                    if (array_search($key, array_column($input['features'], 'key')) === false) {
                        $customPricing[] = array_merge(
                            $flatFeatures[$key],
                            ['value' => $value]
                        );
                    }
                }
            }
            $planPrice = getPlanPrice($customPricing);

            $planPrice = ($activePlan->interval == 2) ? round((($planPrice * 12) - ($planPrice * 2)), 2) : $planPrice;

            if (!empty($activePlan->stripe_addon_plan_id)) {
                $company->subscription('default')->removePrice($activePlan->stripe_addon_plan_id);
            }

            $customPricing = collect($customPricing)->flatMap(function ($feature) {
                return [
                    $feature['key'] => $feature['value'] ?? false,
                ];
            })->toArray();
            if (count($customPricing) >= 1) {
                $metadata['old_addon_id'] = $activePlan->stripe_addon_plan_id;
                $metadata['old_price'] = $activePlan->price;

                $activePlan->update([
                    'addons_description' => count($customPricing) >= 1 ? json_encode($customPricing) : null,
                    'price' => $basePlan->price + $planPrice,
                    'stripe_addon_plan_id' => $input['stripePriceOD']
                ]);
            } else {
                $activePlan->delete();
                $activePlan = $basePlan;
            }
        } else {
            $planPrice = getPlanPrice($input['features']);

            $planPrice = ($activePlan->interval == 2) ? round((($planPrice * 12) - ($planPrice * 2)), 2) : $planPrice;

            $planId = 'addon-' . Str::slug(($company->name . $company->id . ' ' . rand(1000, 9999)), '-');

            /* $product = Product::create([
                'name' => $planId,
            ]); */
            $stripeAddOnProductId = env('STRIPE_ADDON_PROD_ID', 'prod_MbebRSQXScJ4sV');

            $plan = Plan::create([
                'type' => $planId,
                'name' => $planId,
                'description' => is_array($activePlan->description) ? json_encode($activePlan->description) : $activePlan->description,
                'addons_description' => is_array($planDescription) ? json_encode($planDescription) : $planDescription,
                'stripe_plan_id' => $activePlan->stripe_plan_id,
                'stripe_addon_plan_id' => $input['stripePriceOD'],
                'stripe_product_id' => $stripeAddOnProductId,
                'price' =>  $activePlan->price + $planPrice,
                'interval' => $activePlan->interval,
                'base_plan_id' => $activePlan->is_custom_plan ? $activePlan->base_plan_id : $activePlan->id
            ]);

            $activePlan = $company->plan()->save($plan);
        }

        return $this->updateAddonSubscription($company, $activePlan, $metadata);
    }

    public function verify(Request $request)
    {
        $company = $request->user()->company;

        if (!$company->subscription('default')) {
            return response()->json([
                'status' => false,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'You are now subscribed to new plan successfully.',
        ]);
    }

    /**
     * Delete Incomplete payment
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function incomplete(Request $request)
    {
        $company = $request->user()->company;
        $subscription = $company->subscription('default');
        if ($subscription && $subscription->hasIncompletePayment()) {
            $subscription->delete();
        }

        return null;
    }

    /**
     * Get a validator for an incoming subscription update request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'plan' => ['required', 'string', 'exists:plans,type', 'not_archived', 'update_plan', 'downgrade_plan'],
        ]);
    }

    /**
     * Update Payment Method
     *
     * @param  Request  $request
     * @param  Company  $company
     * @param  Plan  $plan
     * @return JsonResponse
     */
    protected function updateSubscription(Request $request, Company $company, Plan $plan)
    {
        $client_reference_id = $request->input('referral') ?? null;
        $coupon_code = $request->input('coupon_code') ?? null;

        if ($coupon_code && $coupon_code == env('UNLOCK_PROGRAM_COUPON_CODE')) {
            $validCoupon = Coupon::where("coupon_code", $coupon_code)->first();
            if ($validCoupon) {
                $periodicDiscount = $company->periodicDiscounts->where('coupon_id', $validCoupon->id)->where('validate_upto', ">=", Carbon::now())->first();
                if ($company->activePlan->id != env('UNLOCK_PROGRAM_SOURCE_PLAN_ID') || !$periodicDiscount) {
                    \Log::error('Update Subscription - Invaliding coupon code : ' . $coupon_code, array_merge($company->toArray(), ['plan_id', $plan->id]));
                    $coupon_code = null;
                }
            } else {
                $coupon_code = null;
                \Log::error('Update Subscription - Invaliding coupon code : ' . $coupon_code, array_merge($company->toArray(), ['plan_id', $plan->id]));
            }
        }

        //try {
            if ($company->subscribed('default')) {
                if ($coupon_code) {
                    //\Log::debug('coupon1', [$coupon_code]);
                    $company->subscription('default')->swapAndInvoice($plan->stripe_plan_id, [
                        'coupon' => $coupon_code,
                    ]);
                    $this->invalidatePerodicDiscount($company, $coupon_code);
                } else {
                    //\Log::debug('coupon2', [$coupon_code]);
                    $company->subscription('default')->swapAndInvoice($plan->stripe_plan_id);
                }
            } elseif ($company->hasPaymentMethod()) {
                $paymentMethod = $company->defaultPaymentMethod();
                $newSubs = $company->newSubscription('default', $plan->stripe_plan_id);
                if ($coupon_code) {
                    //\Log::debug('coupon3', [$coupon_code]);
                    $newSubs->withCoupon($coupon_code);
                    $this->invalidatePerodicDiscount($company, $coupon_code);
                }
                $newSubs->create($paymentMethod->id, [
                    'email' => $company->email,
                    'name' => $company->name,
                ], [
                    'metadata' => ['referral' => $client_reference_id],
                ]);
                // \LaravelFacebookPixel::createEvent('Subscribe', [
                //     'currency' => env('CASHIER_CURRENCY'),
                //     'predicted_ltv' =>  $plan->interval_name,
                //     'value' => $company->id
                // ]);
            } elseif (!$company->hasPaymentMethod()) {
                $newSubs = $company->newSubscription('default', $plan->stripe_plan_id);
                if ($coupon_code) {
                    $newSubs->withCoupon($coupon_code);
                    $this->invalidatePerodicDiscount($company, $coupon_code);
                }
                $coupon = Coupon::where('coupon_code', $coupon_code)->first();
                if (!empty($coupon) && $coupon->trial_duration_in_months) {
                    $now = Carbon::now();
                    $trial_end_date = Carbon::now()->addMonths($coupon->trial_duration_in_months);
                    $newSubs->trialDays($trial_end_date->diffInDays($now));
                }
                $newSubs->create(null, [
                    'email' => $company->email,
                    'name' => $company->name,
                ], [
                    'metadata' => ['referral' => $client_reference_id],
                ]);
                // \LaravelFacebookPixel::createEvent('Subscribe', [
                //     'currency' => env('CASHIER_CURRENCY'),
                //     'predicted_ltv' =>  $plan->interval_name,
                //     'value' => $company->id
                // ]);
            } else {
                return $this->updateCard('card', 'Invalid Payment Card', 'Update Your Credit/Debit Card');
            }

            if (!empty($plan->stripe_addon_plan_id)) {
                $company->subscription('default')
                    ->addPriceAndInvoice($plan->stripe_addon_plan_id, 1, [
                        'proration_behavior' => 'always_invoice',
                        'payment_behavior' => 'error_if_incomplete'
                    ])
                    ->update([
                        'plan_price' => $plan->price,
                        'amount_charged' => $plan->price,
                    ]);
            }

            // delete old plan if exists.
            // todo if user switch back to normal package.
            Plan::where('company_id', $company->id)->where('id', '!=', $plan->id)->delete();

            return $this->updated($request, $plan);
        // } catch (IncompletePayment $exception) {
        //     return response()->json(['link' => route(
        //         'cashier.payment',
        //         [$exception->payment->id, 'redirect' => env('APP_URL') . '/subscription/plans']
        //     )]);
        // } catch (\Exception $e) {
        //     \Log::error('Update Subscription ' . $e->getMessage(), $company->toArray());

        //     return $this->failed($e);
        // }
    }

    protected function updateAddonSubscription(Company $company, Plan $plan, $metadata)
    {
       try {
            $company_subscription = $company->subscription('default');
            $stripe = new \Stripe\StripeClient(config('stripe.secret'));
            if ($plan->stripe_addon_plan_id) {
                $subscription = $stripe->subscriptions->update(
                    $company_subscription->stripe_id,
                    [
                        "metadata" => $metadata,
                        'proration_behavior' => 'always_invoice',
                        'items' => [
                          [
                           'price' => $plan->stripe_addon_plan_id,
                          ],
                        ],
                      'billing_cycle_anchor' => 'now',
                      'expand' => ['latest_invoice.payment_intent']]
                );
                foreach ($subscription['items']['data'] as $value) {
                    if (SubscriptionItem::where('stripe_id', $value->id)->count() == 0) {
                        $company_subscription->items()->create([
                            'stripe_id' => $value->id,
                            'stripe_product' => $value->price->product,
                            'stripe_price' => $value->price->id,
                            'quantity' => $value->quantity ?? null,
                        ]);
                    }
                }
                if ($subscription->latest_invoice->payment_intent && $subscription->latest_invoice->payment_intent->status == 'requires_action') {
                    return response()->json([
                        'link' => route(
                            'cashier.payment',
                            [$subscription->latest_invoice->payment_intent->id,
                            'redirect' => route('subscriptions.index')]
                        ),
                        'message' => __('3D secure payment, action required.'),
                    ]);
                }
                $company_subscription
                    ->update([
                        'stripe_price' => null,
                        'plan_price' => $plan->price,
                        'amount_charged' => $plan->price,
                    ]);

                $company->plans()->where([
                    'base_plan_id' => null,
                    'stripe_addon_plan_id' => null,
                    'stripe_product_id' => null,
                ])->delete();
            } else {
                $company_subscription
                    ->update([
                        'stripe_price' => $plan->stripe_plan_id,
                        'plan_price' => $plan->price,
                        'amount_charged' => $plan->price,
                    ]);
                $stripe->subscriptions->update($company_subscription->stripe_id, ['billing_cycle_anchor' => 'now']);
            }

            return response()->json([
                'message' => __('You are now subscribed to new plan successfully.'),
            ]);
        } catch (IncompletePayment $exception) {
            return response()->json(['link' => route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('subscriptions.index')]
            )]);
        } catch (\Exception $e) {
            \Log::error('Update Subscription ' . $e->getMessage(), $company->toArray());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function invalidatePerodicDiscount(Company $company, $coupon_code)
    {
        if ($coupon_code == env('UNLOCK_PROGRAM_COUPON_CODE')) {
            $coupon = Coupon::where('coupon_code', $coupon_code)->first();
            $company->periodicDiscounts()->where('coupon_id', $coupon->id)->update(['validate_upto' => Carbon::now()]);
        }
    }

    /**
     * The user has to update his/her card.
     *
     * @param $link
     * @param $title
     * @param  string  $message
     * @return JsonResponse
     */
    protected function updateCard($link, $title, $message)
    {
        return response()->json([
            'errors' => [
                'title' => $title,
                'link' => $link,
                'message' => $message,
            ],
        ], 422);
    }

    /**
     * The user billing has been updated.
     *
     * @param  Request  $request
     * @param  Plan  $plan
     * @return JsonResponse
     */
    protected function updated(Request $request, Plan $plan)
    {
        $stripeService = new StripeService();
        $stripeService->onPaymentComplete($request->user(), $plan, $request->input('coupon_code') ?? null);
        return response()->json([
            'message' => __('message.subscribed_successfully'),
        ]);
    }

    /**
     * The user billing update has been failed.
     *
     * @param  \Exception  $e
     * @return JsonResponse
     */
    protected function failed(\Exception $e)
    {
        $message = $e->getMessage();
        $pos = strpos($message, 'invalid payment method');
        if ($pos >= 0) {
            return $this->updateCard('support', 'Please contact support.', $e->getMessage());
        }

        return response()->json([
            'errors' => [
                'plan' => [$e->getMessage()],
            ],
        ], 422);
    }

    /**
     * Fetch user subscription details
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function subscriptionDetails(Request $request)
    {
        $responseArr = [];
        $company = $request->user()->company;
        $subscription = $company->subscription('default');
        $responseArr['planActive'] = false;
        $stripe = new StripeClient(config('stripe.secret'));
        $coupon_name = '';
        if ($subscription && !$subscription->cancelled() && $company->hasStripeId()) {
            $responseArr['planActive'] = true;
            $subscription_details = $stripe->subscriptions->retrieve(
                $subscription->stripe_id,
                []
            );
            if ($subscription_details) {
                if ($subscription_details['discount'] && $subscription_details['discount']['coupon']) {
                    $coupon_name = $subscription_details['discount']['coupon']['id'];
                }
            }
        }
        if ($company->stripeId()) {
            $stripe_cust = $stripe->customers->retrieve($company->stripeId(), []);
            if ($stripe_cust) {
                $responseArr['credits'] = -($stripe_cust['balance'] / 100);
                if (empty($coupon_name) && $stripe_cust['discount'] && $stripe_cust['discount']['coupon']) {
                    $coupon_name = $stripe_cust['discount']['coupon']['id'];
                }
            }
        }
        if (!empty($coupon_name)) {
            $coupon = Coupon::select(['coupon_code', 'name', 'discount_type', 'discount_value', 'duration_type', 'duration_in_months', 'plan_id', 'trial_duration_in_months'])->where('coupon_code', $coupon_name)->first();
            if (!empty($coupon)) {
                $coupon['text'] = $coupon->description_subscription;
                $plan = $coupon->plan;
                $responseArr['coupon'] = $coupon;
                if ($plan && $plan->interval == 2) {
                    $coupon['text'] = $coupon->description_subscription_yearly;
                }
            }
        }
        return response()->json([
            'data' => $responseArr,
        ], 200);
    }

    /**
     * Display plan customizer and fetch user subscription details
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function addons(Request $request)
    {
        $company = $request->user()->company;
        $activePlan = $company->activePlan() ?? freePlan();
        $subscription = $company->subscription('default');
        $activePlan['coupon_code'] = '';
        if ($subscription && !$subscription->cancelled() && $company->hasStripeId()) {
            $stripe = new StripeClient(env('STRIPE_SECRET'));
            $subscription_details = $stripe->subscriptions->retrieve(
                $subscription->stripe_id,
                []
            );
            if ($subscription_details) {
                if ($subscription_details['discount'] && $subscription_details['discount']['coupon']) {
                    $coupon_name = $subscription_details['discount']['coupon']['id'];
                    $coupon = Coupon::select(['coupon_code'])->where('coupon_code', $coupon_name)->first();
                    if (!empty($coupon)) {
                        $activePlan['coupon_code'] = $coupon_name;
                    }
                }
            }
            $invoice = $this->getUpcomingInvoice($company, ['subscription_proration_behavior' => 'none']);
            $activePlan['amount_charged'] = $invoice->total / 100;
            if (isset($company->activePlan, $company->activePlan()->basePlan)) {
                $basePlan = $company->activePlan->basePlan->description;
            } else {
                $basePlan = optional($company->activePlan)->description ?? null;
            }
            return Inertia::render('BuyAddons', compact('company', 'activePlan', 'basePlan'));
        } else {
            return redirect()->route('subscriptions.index');
        }
    }

}
