<?php

namespace App\Validators;

use App\Models\Plan;

class DowngradePlan
{
    public function validate(
        $attribute,
        $value,
        $parameters,
        $validator
    ) {
        if (auth()->check()) {
            $plan = auth()->user()->company->activePlan();
            if (empty($plan)) {
                return true;
            }

            $futurePlan = Plan::where('type', '=', $value)->first();
            if (empty($futurePlan)) {
                return false;
            }

            if (count(auth()->user()->company->user) <= $futurePlan->description['total_users']) {
                return true;
            }

            if ($futurePlan->description['total_users'] > $plan->description['total_users']) {
                return true;
            }
        }

        return false;
    }
}
