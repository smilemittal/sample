<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncCouponPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instawp:sync_coupons_plans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync coupon and plans';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $coupons = Coupon::all();
        $allPlanIds = Plan::where('name', '!=', 'Free')->whereNull('user_id')->pluck('id')->toArray();
        foreach ($coupons as $coupon) {
            $coupon->plans()->attach($coupon->plan_id ?? $allPlanIds);
        }
        $this->info('Coupon synced with plans successfully');
    }
}
