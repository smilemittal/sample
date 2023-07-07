<?php

namespace App\Listeners;

use App\Events\GroupOrderConfirmed;
use App\Events\OrderConfirmed;
use App\Interfaces\CommonConstants;
use App\Mail\BeforeTrialEnds;
use App\Mail\OrderCreated;
use App\Mail\OrderCreatedSeller;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Services\StripeService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Events\WebhookReceived;
use Stripe\StripeClient;

class StripeEventListener
{
    /**
     * Handle received Stripe webhooks.
     *
     * @param  \Laravel\Cashier\Events\WebhookReceived  $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        if ($event->payload['type'] === 'customer.subscription.trial_will_end') {
            $subscription = $event->payload['data']['object'];
            $user = User::where('stripe_id', $subscription['customer'])->first();
            $plan = Plan::where('stripe_plan_id', $subscription['plan']['id'])->first();

            if (!empty($user)) {
                $data = [];
                $data['name'] = $user->name;
                $data['pm_type'] = $user->pm_type;
                $data['pm_last_four'] = $user->pm_last_four;
                $data['trial_end'] = gmdate('Y-m-d H:i', $subscription['trial_end']);
                $data['plan_amount'] = $subscription['plan']['amount'] / 100;
                $data['plan_name'] = (!empty($plan)) ? $plan->name : '';
                Mail::to($user->email)->queue((new BeforeTrialEnds($data))->onQueue('emails'));
                Log::info('stripe event customer.subscription.trial_will_end: mail sent.', ['customer' => $subscription['customer']]);
            } else {
                Log::error('stripe event customer.subscription.trial_will_end: user not found.', ['customer' => $subscription['customer']]);
            }
        } elseif ($event->payload['type'] === 'invoice.payment_failed') {
            $data = $event->payload['data']['object'];
            \Log::debug('invoice.payment_failed', [$data]);
            if (!empty($data['payment_intent'])) {
                $stripe = new \Stripe\StripeClient(config('stripe.secret'));
                $intent_status = $stripe->paymentIntents->retrieve($data['payment_intent'])->status;
                if ($intent_status == 'requires_action') {
                    return $intent_status;
                }
            }
            $billing_reason = $data['billing_reason'];
            $customer_id  = $data['customer'];
            $user = User::where('stripe_id', $customer_id)->first();
            $plan_data = $data['lines']['data'];
            $revert_plan = true;
            $paid = $data['paid'];
            $status = $data['status'];
            if (count($plan_data) > 1) {
                $revert_plan = false;
            }
            if (!$paid && $billing_reason == 'subscription_create' && $revert_plan && (!empty($status) && $status == 'open')) {
                try {
                    $user->subscription('default')->cancelNow();
                    Log::info('stripe event invoice.payment_failed: Subscription canceled.', ['customer' => $data['customer']]);
                } catch (Exception $ex) {
                    report($ex);
                }
            }
        } elseif ($event->payload['type'] === 'invoice.paid') {
            $data = $event->payload['data']['object'];
            \Log::debug('invoice.paid', [$data]);
            $this->invoicePaid($data);
        }
    }
   
    public function invoicePaid($data) {
        $customer_id  = $data['customer'];
        $user = User::where('stripe_id', $customer_id)->first();
        //if ($user->subscription('default')->hasIncompletePayment()) {
            $billing_reason = $data['billing_reason'];
            $paid = $data['paid'];
            $status = $data['status'];
            $coupon_code = null;
            if (!empty($data['discount']) && !empty($data['discount']['coupon'])) {
                $coupon_code  = $data['discount']['coupon']['id'];
            }
            Log::info('invoice.paid', [$paid && (!empty($status) && $status == 'paid')]);
        
            if ($paid && (!empty($status) && $status == 'paid')) {
                try {
                    $user->subscription('default')->syncStripeStatus();
                    $metadata = [];
                    if ($billing_reason == 'subscription_update') {
                        foreach ($data['lines']['data'] as $s_item) {
                            $metadata = $metadata + $s_item['metadata'];
                            if (count($s_item['metadata']) > 1 && !empty($s_item['metadata']['is_custom'])) {
                                $user->plans()->where([
                                    'base_plan_id' => null,
                                    'stripe_addon_plan_id' => null,
                                    'stripe_product_id' => null,
                                ])->delete();
                                $activePlan = $user->activePlan();
                                $user->subscription('default')
                                    ->update([
                                        'plan_price' => $activePlan->price,
                                        'amount_charged' => $activePlan->price,
                                    ]); 
                                Log::info('stripe event invoice.paid: Addon activated.', ['customer' => $data['customer']]);  
                                break;                                
                            }
                        }
                        Log::info("Subscription Meta Data", $metadata);
                        $metadata = Arr::except($metadata, ['is_custom','price_id', 'old_addon_id', 'old_description', 'old_price', 'activePlan']);
                        $stripe = new StripeClient(config('stripe.secret'));
                        $stripe->subscriptions->update(
                            $user->subscription('default')->stripe_id,
                            ['metadata' => null]
                        ); 
                        $stripe->subscriptions->update(
                            $user->subscription('default')->stripe_id,
                            ['metadata' => $metadata]
                        ); 
                    } 
                    if (in_array($billing_reason, ['subscription_create', 'subscription_update'])) {
                        $plan = $user->activePlan();
                        $stripeService = new StripeService();
                        $stripeService->onPaymentComplete($user, $plan, $coupon_code);
                        Log::info('stripe event invoice.paid: Subscription updated.', ['customer' => $data['customer']]);  
                    }
                   
                } catch (Exception $ex) {
                    Log::error('stripe event invoice.paid:', ['customer' => $data['customer']]);  
                    report($ex);
                }
            }
        //}
    }

}
