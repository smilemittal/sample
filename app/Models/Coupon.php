<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function getDescriptionTextAttribute()
    {
        $value_txt = $this->discount_type == 'amount' ? '$' . $this->discount_value : $this->discount_value . '%';
        $dur_text = '';

        $plans = $this->plans->pluck('name')->toArray();
        $plan_name = $plans ? ' (Applied to "' . implode(',', $plans) . '" plan)' : '';

        if ($this->duration_type == 'once') {
            $dur_text = 'once';
        } elseif ($this->duration_type == 'repeating') {
            $dur_text = ($this->duration_in_months > 1) ? $this->duration_in_months . ' months' : $this->duration_in_months . ' month';
        } else {
            $dur_text = 'forever';
        }

        $trial_text = '.';
        if ($this->trial_duration_in_months) {
            $trial_dur = ($this->trial_duration_in_months > 1) ? $this->trial_duration_in_months . ' months.' : $this->trial_duration_in_months . ' month.';
            $trial_text = ' and free trial for ' . $trial_dur;
        }

        return 'You have successfully applied "' . $this->coupon_code . '". You will get ' . $value_txt . ' discount for ' . $dur_text . $trial_text . $plan_name;
    }

    public function getDescriptionTextYearlyAttribute()
    {
        $value_txt = $this->discount_type == 'amount' ? '$' . $this->discount_value : $this->discount_value . '%';
        $dur_text = '';

        $plans = $this->plans->pluck('name')->toArray();
        $plan_name = $plans ? ' (Applied to "' . implode(',', $plans) . '" plan)' : '';

        if ($this->duration_type == 'once') {
            $dur_text = '1 year';
        } elseif ($this->duration_type == 'repeating') {
            $years = ceil($this->duration_in_months / 12);
            $dur_text = ($years > 1) ? $years . ' years' : $years . ' year';
        } else {
            $dur_text = 'forever';
        }

        $trial_text = '.';
        if ($this->trial_duration_in_months) {
            $trial_dur = ($this->trial_duration_in_months > 1) ? $this->trial_duration_in_months . ' months.' : $this->trial_duration_in_months . ' month.';
            $trial_text = ' and free trial for ' . $trial_dur;
        }

        return 'You have successfully applied "' . $this->coupon_code . '". You will get ' . $value_txt . ' discount for ' . $dur_text . $trial_text . $plan_name;
    }

    public function getDescriptionSubscriptionAttribute()
    {
        $value_txt = $this->discount_type == 'amount' ? '$' . $this->discount_value : $this->discount_value . '%';
        $dur_text = '';
        if ($this->duration_type == 'once') {
            $dur_text = '1 month';
        } elseif ($this->duration_type == 'repeating') {
            $dur_text = ($this->duration_in_months > 1) ? $this->duration_in_months . ' months' : $this->duration_in_months . ' month';
        } else {
            $dur_text = 'forever';
        }
        $trial_text = '.';
        if ($this->trial_duration_in_months) {
            $trial_dur = ($this->trial_duration_in_months > 1) ? $this->trial_duration_in_months . ' months.' : $this->trial_duration_in_months . ' month.';
            $trial_text = ' and free trial for ' . $trial_dur;
        }

        return 'Active Coupon "' . $this->coupon_code . '". ' . $value_txt . ' discount, ' . $dur_text . $trial_text;
    }

    public function getDescriptionSubscriptionYearlyAttribute()
    {
        $value_txt = $this->discount_type == 'amount' ? '$' . $this->discount_value : $this->discount_value . '%';
        $dur_text = '';

        if ($this->duration_type == 'once') {
            $dur_text = '1 year';
        } elseif ($this->duration_type == 'repeating') {
            $years = ceil($this->duration_in_months / 12);
            $dur_text = ($years > 1) ? $years . ' years' : $years . ' year';
        } else {
            $dur_text = 'forever';
        }

        $trial_text = '.';
        if ($this->trial_duration_in_months) {
            $trial_dur = ($this->trial_duration_in_months > 1) ? $this->trial_duration_in_months . ' months.' : $this->trial_duration_in_months . ' month.';
            $trial_text = ' and free trial for ' . $trial_dur;
        }

        return 'Active Coupon "' . $this->coupon_code . '". ' . $value_txt . ' discount, ' . $dur_text . $trial_text;
    }

    public function getDescriptionTextPlanAttribute()
    {
        $value_txt = $this->discount_type == 'amount' ? '$' . $this->discount_value : $this->discount_value . '%';
        $dur_text = '';
        if ($this->duration_type == 'once') {
            $dur_text = '1 month';
        } elseif ($this->duration_type == 'repeating') {
            $dur_text = ($this->duration_in_months > 1) ? $this->duration_in_months . ' months' : $this->duration_in_months . ' month';
        } else {
            $dur_text = 'forever';
        }
        $trial_text = '.';
        if ($this->trial_duration_in_months) {
            $trial_dur = ($this->trial_duration_in_months > 1) ? $this->trial_duration_in_months . ' months.' : $this->trial_duration_in_months . ' month.';
            $trial_text = ' and free trial for ' . $trial_dur;
        }

        return 'Active Coupon "' . $this->coupon_code . '". You got ' . $value_txt . ' discount for ' . $dur_text . $trial_text;
    }

    public function getDescriptionTextPlanYearlyAttribute()
    {
        $value_txt = $this->discount_type == 'amount' ? '$' . $this->discount_value : $this->discount_value . '%';
        $dur_text = '';

        if ($this->duration_type == 'once') {
            $dur_text = '1 year';
        } elseif ($this->duration_type == 'repeating') {
            $years = ceil($this->duration_in_months / 12);
            $dur_text = ($years > 1) ? $years . ' years' : $years . ' year';
        } else {
            $dur_text = 'forever';
        }

        $trial_text = '.';
        if ($this->trial_duration_in_months) {
            $trial_dur = ($this->trial_duration_in_months > 1) ? $this->trial_duration_in_months . ' months.' : $this->trial_duration_in_months . ' month.';
            $trial_text = ' and free trial for ' . $trial_dur;
        }

        return 'Active Coupon "' . $this->coupon_code . '". You got ' . $value_txt . ' discount for ' . $dur_text . $trial_text;
    }

    /**
     * deprecated ad now coupon can be applied to multiple plan
     */
    // public function plan()
    // {
    //     return $this->belongsTo(Plan::class);
    // }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'coupon_plan');
    }
}
