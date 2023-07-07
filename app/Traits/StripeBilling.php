<?php

namespace App\Traits;

use Stripe\Price as StripePrice;
use Stripe\Stripe;

trait StripeBilling
{
    
    /**
     * Create price on stripe
     *
     * @param  array $data
     * @return StripePrice
     */
    public function createPrice($data = [])
    {
        Stripe::setApiKey(config('stripe.secret'));
        return StripePrice::create([
            'product' => $data['stripe_product_id'],
            'currency' => $data['currency'],
            'unit_amount' => $data['price'] * 100,
            'recurring[interval]' => $data['interval'],
        ]);
    }

    /**
     * Create price on stripe
     *
     * @param  array $data
     * @return Stripe
     */
    public function getUpcomingInvoice($user, $data = [])
    {
        Stripe::setApiKey(config('stripe.secret'));
        return $user->company->subscription('default')->upcomingInvoice($data);
    }

}