<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CreateStripeCouponCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instawp:create_coupon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for create coupon codes.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $request_data = [];
        $number_of_coupons = $this->ask('How many coupons do you want to generate?', 1);
        if ($number_of_coupons == 1) {
            $name = $this->ask('Name of Coupon');
            $request_data['name'] = $name;
        }
        $off_type = $this->choice('Discount Type', ['amount', 'percentage'], 'amount');

        if ($off_type == 'percentage') {
            $off_value = $this->ask('Discount Percentage', 1);
            $request_data['percent_off'] = $off_value;
        } elseif ($off_type == 'amount') {
            $off_value = $this->ask('Discount amount(USD)', 1);
            $request_data['amount_off'] = $off_value * 100;
            $request_data['currency'] = 'usd';
        }

        $duration_type = $this->choice('Duration Type', ['once', 'repeating', 'forever'], 'once');
        $request_data['duration'] = $duration_type;
        if ($duration_type == 'repeating') {
            $duration_in_months = $this->ask('Duration In Months');
            $request_data['duration_in_months'] = intval($duration_in_months);
        }

        $max_redemptions = $this->ask('Maximum Redemptions', 1);
        $request_data['max_redemptions'] = $max_redemptions;

        $redeem_by = $this->ask('Redeem By days (0 for no limit)', 0);
        if ($redeem_by) {
            $request_data['redeem_by'] = strtotime('+'.$redeem_by.' days');
        }
        $plans = Plan::where('name', '!=', 'Free')->whereNull('user_id')->pluck('name')->toArray();
        $allKey = 'all';
        array_unshift($plans, $allKey);
        $plan_name = $this->choice('Select plans (comma seperated values)', $plans, 0, null, true);
        $plan_ids = [];
        if (in_array($allKey, $plan_name)) {
            $plan_ids = Plan::where('name', '!=', 'Free')->whereNull('user_id')->pluck('id')->toArray();
        } else {
            $plan_ids = Plan::whereIn('name', $plan_name)->pluck('id')->toArray();
        }
        $coupon_target = $this->ask('Target name');
        $trial_duration_in_months = 0;
        if ($off_type == 'percentage' && $off_value == 100) {
            //$trial_duration_in_months = $duration_type == 'repeating' ? $duration_in_months : 1;
        } else {
            $trial_duration_in_months = $this->ask('Trial Duration In Months', 0);
        }

        if ($number_of_coupons == 1) {
            $coupon_code = $this->ask('Unique Coupon Code', '');
            if ($coupon_code) {
                $request_data['id'] = $coupon_code;
            }
        } else {
            $coupon_code = $this->ask('Unique Coupon Code Prefix', '');
        }

        // $request_data['applies_to'] = ['products' => [env('STRIPE_PROD_ID')]];
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        try {
            if ($number_of_coupons == 1) {
                $response = $stripe->coupons->create($request_data);
                \Log::info('coupon created', [$response]);
                if ($response->valid) {
                    $store = new Coupon();
                    $store->name = $response->name;
                    $store->target = $coupon_target ?? null;
                    $store->trial_duration_in_months = $trial_duration_in_months ? $trial_duration_in_months : null;
                    $store->coupon_code = $response->id;
                    $store->discount_type = $off_type;
                    $store->discount_value = ($off_type == 'amount') ? $response->amount_off / 100 : $response->percent_off;
                    $store->duration_type = $response->duration;
                    $store->duration_in_months = $duration_type == 'repeating' ? $response->duration_in_months : null;
                    if (! $store->save()) {
                        $this->error('Coupon not saved to database.');
                    }
                    $store->plans()->attach($plan_ids);
                    $this->info('Discount Coupon Created: '.$response->id);
                }
            } else {
                $filename = $coupon_code ? $coupon_code.'_coupons.csv' : time().'_coupons.csv';

                $path = 'coupons/';
                if (! Storage::exists($path)) {
                    Storage::makeDirectory($path);
                }
                $file = Storage::path($path.$filename);
                $handle = fopen($file, 'w');

                for ($i = 1; $i <= (int) $number_of_coupons; $i++) {
                    if ($coupon_code) {
                        $request_data['id'] = $coupon_code.'_'.$this->generateRandomString(12);
                    }

                    $response = $stripe->coupons->create($request_data);
                    \Log::info('coupon created', [$response]);
                    if ($response->valid) {
                        $store = new Coupon();
                        $store->name = $response->id;
                        $store->target = $coupon_target ?? null;
                        $store->trial_duration_in_months = $trial_duration_in_months ?? null;
                        $store->coupon_code = $response->id;
                        $store->discount_type = $off_type;
                        $store->discount_value = ($off_type == 'amount') ? $response->amount_off / 100 : $response->percent_off;
                        $store->duration_type = $response->duration;
                        $store->duration_in_months = $duration_type == 'repeating' ? $response->duration_in_months : null;
                        if (! $store->save()) {
                            $this->error('Coupon not saved to database.');
                        }
                        $store->plans()->attach($plan_ids);
                        fputcsv($handle, [
                            $response->id,
                        ]);

                        $this->info('Discount Coupon Created: '.$response->id);
                    }
                }
                fclose($handle);
                $this->info('Please Download the CSV File: '.$file);
            }
        } catch (\Stripe\Exception\CardException $e) {
            $this->error($e->getMessage());
        }
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
