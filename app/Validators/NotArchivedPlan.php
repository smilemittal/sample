<?php

namespace App\Validators;

use App\Models\Plan;

class NotArchivedPlan
{
    public function validate(
        $attribute,
        $value,
        $parameters,
        $validator
    ) {
        if (auth()->check()) {
            $plan = Plan::withTrashed()->where('type', '=', $value)->first();
            if (empty($plan->deleted_at)) {
                return true;
            }
        }

        return false;
    }
}
