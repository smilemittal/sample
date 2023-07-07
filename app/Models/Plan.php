<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Plan extends Model
{
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'plans';

    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        /*'description' => 'array'*/];

    protected $appends = ['is_custom_plan', 'number_of_addons', 'base_plan'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];


    public const Active = 'Active';
    public const InActive = 'In-Active';
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function basePlan()
    {
        return $this->belongsTo(self::class, 'base_plan_id', 'id')->withTrashed();
    }

    public function getIsCustomPlanAttribute()
    {
        return !empty($this->company_id) && !empty($this->base_plan_id);
    }

    public function getIntervalNameAttribute()
    {
        return $this->interval == 1 ? 'Monthly' : 'Yearly';
    }

    public function getNumberOfAddonsAttribute(): int
    {
        if (!$this->is_custom_plan) {
            return 0;
        } else {
            return $this->addons_description ? count(json_decode($this->addons_description, true)) : 0;
        }
    }

    public function getBasePlanAttribute()
    {
        return $this->basePlan()->first();
    }

    public function getDescriptionJsonAttribute()
    {
        return $this->description ? json_encode($this->description) : null;
    }
    public function getDescriptionAttribute($value)
    {
        if ($this->addons_description && $this->stripe_addon_plan_id) {
            $bases = $this->basePlan->description;
            $addon = json_decode($this->addons_description, true);
            $desc = [];
            foreach ($bases as $key => $base) {
                if (isset($addon[$key])) {
                    if (is_int($base)) {
                        $desc[$key] =  $addon[$key] + $base;
                    } else {
                        $desc[$key] =  $addon[$key];
                    }
                } else {
                    $desc[$key] = $base;
                }
            }
            foreach ($addon as $key => $base) {
                if (!isset($desc[$key])) {
                    $desc[$key] =  $base;
                }
            }
            return $desc;
        }

        return json_decode($value, true);
    }

    public function getMissingFeaturesAttribute()
    {
        $missing_features = [];
        if (file_exists(resource_path('json/features.json'))) {
            $features_json = file_get_contents(resource_path('json/features.json'));
            $features = json_decode($features_json, true);
            $plan_description = $this->description;
            foreach ($features as $sub_features) {
                foreach ($sub_features['features'] as $sub_feature) {
                    if (!array_key_exists($sub_feature['key'], $plan_description)) {
                        $missing_features[$sub_feature['key']] = $sub_feature['value'];
                    }
                }
            }
        }

        return $missing_features;
    }

    public function getHasMissingFeaturesAttribute()
    {
        return count($this->missing_features);
    }

    public function getDescriptionValueUsingKey($key)
    {
        return array_key_exists($key, $this->description) ? $this->description[$key] : null;
    }
}
