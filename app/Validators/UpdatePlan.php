<?php

namespace App\Validators;

class UpdatePlan
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

            return $plan->type !== $value;
        }

        return false;
    }
}
