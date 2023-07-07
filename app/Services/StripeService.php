<?php

namespace App\Services;

use App\Mail\NewServerAssignMail;
use App\Models\Coupon;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\CreditHistory;
use App\Services\Service;
use App\Traits\StripeBilling;
use App\Traits\CompanyAuthorization;
use App\Interfaces\CommonConstants;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\Paginator;

class StripeService extends Service
{
    use CompanyAuthorization;
    use StripeBilling;

    /**
     * get Collection on basis of request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed $array
     */
    public function getPrices(Request $request)
    {
        $sub_data = [];
        $request->validate([
            'features' => 'required|array'
        ]);
        $input = $request->all();
        $company = $request->user()->company;
        $activePlan = $company->activePlan();
        $basePlan = optional($company->activePlan())->basePlan;

        $flatFeatures = collect($request->flatFeatures)->keyBy('key');

        $features = collect($input['features']);
        $planDescription = $features->flatMap(function ($feature) {
            return [
                $feature['key'] => $feature['value'] ?? false,
            ];
        });

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

            $customPricing = collect($customPricing)->flatMap(function ($feature) {
                return [
                    $feature['key'] => $feature['value'] ?? false,
                ];
            })->toArray();
            $stripe_price_id = null;
            if (count($customPricing) >= 1) {
                $stripe_price_id = $this->createPrice([
                    'currency' => env('CASHIER_CURRENCY'),
                    'price' => $planPrice,
                    'interval' => $activePlan->interval == 1 ? 'month' : 'year',
                    'stripe_product_id' => $activePlan->stripe_product_id,
                ])->id;
            }
            $current_stripe_price_id = $activePlan->stripe_addon_plan_id;
            $item = $company->subscription('default')->findItemOrFail($current_stripe_price_id);
            if ($stripe_price_id) {
                $sub_data = ['subscription_proration_behavior' => 'none', 'subscription_items[0][id]' => $item->stripe_id,'subscription_items[0][deleted]' => true, 'subscription_items[1][price]' => $stripe_price_id];
            } else {
                $stripe_price_id = 'removed';
                $sub_data = ['subscription_proration_behavior' => 'none', 'subscription_items[0][id]' => $item->stripe_id,'subscription_items[0][deleted]' => true];
            }
        } else {
            $planPrice = getPlanPrice($input['features']);

            $planPrice = ($activePlan->interval == 2) ? round((($planPrice * 12) - ($planPrice * 2)), 2) : $planPrice;
            $stripeAddOnProductId = env('STRIPE_ADDON_PROD_ID', 'prod_MbebRSQXScJ4sV');
            $stripe_price_id = $this->createPrice([
                'currency' => env('CASHIER_CURRENCY'),
                'price' => $planPrice,
                'interval' => $activePlan->interval == 1 ? 'month' : 'year',
                'stripe_product_id' => $stripeAddOnProductId,
            ])->id;
            $sub_data = ['subscription_proration_behavior' => 'none', 'subscription_items[][price]' => $stripe_price_id];
        }
        $invoiceFull = $this->getUpcomingInvoice($company, $sub_data)->total/100;
        $sub_data['subscription_proration_behavior'] = 'always_invoice';
        $invoiceCurrent = $this->getUpcomingInvoice($company, $sub_data)->total/100;
        return compact('invoiceFull', 'stripe_price_id', 'invoiceCurrent');
    }

    /**
     * The user billing has been updated.
     *
     * @param  User  $user
     * @param  Plan  $plan
     * @param  string|null  $coupon_code
     */
    public function onPaymentComplete(User $user, Plan $plan, $coupon_code = null)
    {
        $planDesc = $plan->description;
        $companyUsed = $this->getComapnyAuth($user->company)['companyUsed'];
       
        \Log::debug('User Plan Updated');
        // $non_public_list_id = env('SENDINBLUE_NONPUBLIC_ID', 64);
        // $free_list_id = env('SENDINBLUE_FREEUSER_ID', 53);
        // $paid_list_id = env('SENDINBLUE_PAIDUSER_ID', 56);
        // $listIds = false;
        // if ($plan->type == 'free') {
        //     $listIds = ($user->is_non_public_email_user) ? [(int) $non_public_list_id, (int) $free_list_id] : [(int) $free_list_id];
        // } else {
        //     $listIds = [(int) $paid_list_id];
        //     $user->allow_plan_customizer = 1;
        // }
        // if ($listIds) {
        //     $user->syncToSendinblue($listIds);
        // }

        // $user->save();

        //subscription table updated data don't remove below line.
        $user = User::find($user->id);
        $stripe = new \Stripe\StripeClient(config('stripe.secret'));
        $user_subscription = $user->company->subscription('default');

        if ($plan->type != 'free') {
            if ($user_subscription) {
                try {
                    //add referral credits to users
                    // if ($user->referrer_id) {
                    //     $referrer = $user->referrer;
                    //     $typeArr = CommonConstants::REFERRAL_PROGRAMS['signup'];
                    //     $referral_program = $referrer->referralprogram()->where('type', $typeArr['type'])->where('status', 1)->first();
                    //     $referralcredit = $user->referralcredit;
                    //     if ($referral_program && $referralcredit === null) {
                    //         $creditArr = CommonConstants::ADD_USER_CREDIT;

                    //         $amount_to_referrer = $referral_program->amount_to_self ? $referral_program->amount_to_self : $creditArr['signup_conversion']['amount'];
                    //         $amount_to_user = $referral_program->amount_to_user ? $referral_program->amount_to_user : $creditArr['plan_purchase']['amount'];
                    //         \Log::info('credited', [$amount_to_referrer,$amount_to_user]);
                    //         //referrer credit
                    //         $referrerStripeCustomer = $referrer->createOrGetStripeCustomer();
                    //         $createCreditArr = [];
                    //         $createCreditArr['stripe_customer_id'] = $referrerStripeCustomer->id;
                    //         $createCreditArr['user_id'] = $referrer->id;
                    //         $createCreditArr['amount'] = $amount_to_referrer;
                    //         $createCreditArr['currency'] = $creditArr['signup_conversion']['currency'];
                    //         $createCreditArr['description'] = $creditArr['signup_conversion']['description'];
                    //         $createCreditArr['credit_note'] = $creditArr['signup_conversion']['credit_note'];
                    //         $createCreditArr['referral_user_id'] = $user->id;
                    //         $this->createUserCredit($createCreditArr);

                    //         //user credit
                    //         $userStripeCustomer = $user->createOrGetStripeCustomer();
                    //         $createCreditArr = [];
                    //         $createCreditArr['stripe_customer_id'] = $userStripeCustomer->id;
                    //         $createCreditArr['user_id'] = $user->id;
                    //         $createCreditArr['amount'] = $amount_to_user;
                    //         $createCreditArr['currency'] = $creditArr['plan_purchase']['currency'];
                    //         $createCreditArr['description'] = $creditArr['plan_purchase']['description'];
                    //         $createCreditArr['credit_note'] = $creditArr['plan_purchase']['credit_note'];
                    //         $this->createUserCredit($createCreditArr);
                    //     }
                    // }

                    $subscription_details = $stripe->subscriptions->retrieve(
                        $user_subscription->stripe_id,
                        []
                    );
                    if ($subscription_details) {
                        \Log::debug('subscription_details', [$subscription_details]);
                        $plan_amount = 0;
                        if (empty($subscription_details->plan)) {
                            foreach ($subscription_details->items as $key => $value) {
                                $plan_amount += $value->price->unit_amount;
                            }
                            $plan_amount = $plan_amount / 100;
                        } else {
                            $plan_amount = $subscription_details->plan->amount / 100;
                        }
                        $user_subscription->plan_price = $plan_amount;
                        $coupon = null;
                        if ($coupon_code) {
                            $coupon = Coupon::where('coupon_code', $coupon_code)->first();
                        }
                        //once type coupon discount not recived in api response so get detail from coupons table.
                        if ($coupon && $coupon->duration_type == 'once') {
                            $user_subscription->coupon_code = $coupon->coupon_code;
                            if ($coupon->discount_type == 'amount') {
                                $user_subscription->amount_charged = $plan_amount - $coupon->discount_value;
                            } elseif ($coupon->discount_type == 'percentage') {
                                $amount_off = ($coupon->discount_value * $plan_amount) / 100;
                                $user_subscription->amount_charged = $plan_amount - $amount_off;
                            }
                        }

                        if ($subscription_details->discount && isset($subscription_details->discount->coupon)) {
                            $user_subscription->coupon_code = $subscription_details->discount->coupon->id;
                            if ($subscription_details->discount->coupon->amount_off) {
                                $amount_off = $subscription_details->discount->coupon->amount_off / 100;
                                $user_subscription->amount_charged = $plan_amount - $amount_off;
                            } elseif ($subscription_details->discount->coupon->percent_off) {
                                $amount_off = ($subscription_details->discount->coupon->percent_off * $plan_amount) / 100;
                                $user_subscription->amount_charged = $plan_amount - $amount_off;
                            }
                        } else {
                            if (!$coupon) {
                                $user_subscription->amount_charged = $plan_amount;
                            }
                        }
                    }
                } catch (\Stripe\Exception\CardException $e) {
                    \Log::error('retrive_stripe_subscription', [$e]);
                }
                $user_subscription->save();
            }
        } else {
            if ($user_subscription) {
                $user_subscription->plan_price = $user_subscription->amount_charged = null;
                $user_subscription->coupon_code = null;
                $user_subscription->save();
            }
        }
    }

    public function revertIncompletePayments()
    {
        $subscriptions = Subscription::where('stripe_status', 'past_due')->whereRaw('updated_at < (NOW() - INTERVAL 30 MINUTE)')
            ->get();
        foreach ($subscriptions as $subscription) {
            $this->revertIncompletePayment($subscription);
        }
    }

    public function revertIncompletePayment(Subscription $subscription)
    {
        $stripe = new \Stripe\StripeClient(config('stripe.secret'));
        $subscription_details = $stripe->subscriptions->retrieve(
            $subscription->stripe_id,
            [
                'expand' => ['latest_invoice.payment_intent']
            ]
        );
        $user = User::where('stripe_id', $subscription_details['customer'])->first();
        if (!empty($user) && count($subscription_details['metadata']) > 1) {
            $metadata = $subscription_details['metadata'];
            $user_subscription = $user->subscription('default');
            $activePlan = $user->activePlan();
            $price_id = $subscription_details['metadata']['price_id'];
            if ($subscription_details['metadata']['is_custom'] == "true") {
                $old_addon_id = $subscription_details['metadata']['old_addon_id'];
                $old_description = $subscription_details['metadata']['old_description'];
                $old_price = $subscription_details['metadata']['old_price'];
                $subscription_item = $subscription->items->where('stripe_price', $price_id)->first();
                $metadata = Arr::except($metadata, ['is_custom','price_id', 'old_addon_id', 'old_description', 'old_price', 'activePlan']);
                $stripe->subscriptions->update(
                    $user_subscription->stripe_id,
                    ['metadata' => null]
                );
                $stripe->subscriptions->update(
                    $user_subscription->stripe_id,
                    [
                        'proration_behavior' => 'none',
                        "metadata" => null,
                        "items" => [
                            [
                                'id' => $subscription_item->stripe_id,
                                'price' => $old_addon_id,
                            ]
                        ]
                    ]
                );
                $activePlan->update([
                    'addons_description' => $old_description,
                    'price' => $old_price,
                    'stripe_addon_plan_id' => $old_addon_id
                ]);
                $user_subscription->update([
                     'plan_price' => $activePlan->price,
                    'amount_charged' => $activePlan->price,
                ]);
            } else {
                $user_subscription->removePrice($price_id);
                $stripe->subscriptions->update(
                    $user_subscription->stripe_id,
                    ['metadata' => null]
                );
                $basePlan = optional($user->activePlan())->basePlan;
                if ($basePlan) {
                    $activePlan->delete();
                    $plan = $basePlan;
                } else {
                    $plan = $activePlan;
                }
                $user_subscription->update([
                    'stripe_price' => $plan->stripe_plan_id,
                    'plan_price' => $plan->price,
                    'amount_charged' => $plan->price,
                ]);
            }
            $metadata = Arr::except($metadata, ['is_custom','price_id', 'old_addon_id', 'old_description', 'old_price', 'activePlan']);
            $stripe->subscriptions->update(
                $user_subscription->stripe_id,
                ['metadata' => $metadata]
            );
            $stripe->invoices->voidInvoice(
                $subscription_details['latest_invoice']['id'],
                []
            );
            //$stripe->subscriptions->update($user_subscription->stripe_id, ['billing_cycle_anchor' => 'now']);

            $user_subscription->syncStripeStatus();
        }
    }
   
    /**
     * get Collection on basis of request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed $invoices
     */
    public function getInvoiceList(Request $request)
    {
        $user = $request->user()->company;
        $invoices = $user->invoices();
        $perPage = $request->has('per_page') && is_numeric($request->per_page) && $request->per_page > 0 ? $request->per_page : 10;
        $currentPage = $request->input("page") ?? 1;
        $startingPoint = ($currentPage * $perPage) - $perPage;
        $total = $invoices->count();
        return new Paginator($invoices->slice($startingPoint, $perPage), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
            'total' => $total,
            'last_page' => round($total / $perPage),
        ]);
    }
}
