<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class Company extends Model
{
    use Billable, SoftDeletes, Notifiable;

    protected $guarded = ['id'];
    protected $hidden = [
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
    ];


    protected $dates = ['archived_at'];
    const DELETED_AT = 'archived_at';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'active_plan', 'has_payment_method'
    ];

    public function scopeScpCompany($query)
    {
        return User::hasRole(Auth::user(), 'superadmin') ? $query : $query->where('id', Auth::user()->company_id);
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'company_id', 'id');
    }

    // public function groups()
    // {
    //     return $this->hasMany(Group::class, 'company_id', 'id');
    // }

    public function user()
    {
        return $this->hasMany(\App\Models\User::class, 'company_id', 'id');
    }

    // this is a recommended way to declare event handlers
    public static function boot()
    {
        parent::boot();
        self::deleting(function ($company) { // before delete() method call this
            $company->user()->each(function ($item) {
                $item->delete(); // <-- direct deletion
            });
            $company->groups()->each(function ($item) {
                $item->delete(); // <-- direct deletion
            });
        });
    }

    public function list(
        $filterArr = false,
        $fields = false,
        $orderBy = false,
        $order = false,
        $isArchived = false,
        $perPage = false
    ) {
        if (!$fields) {
            $fields = ['*'];
        }
        $query = Company::select($fields);
        if ($isArchived) {
            $query = $query->onlyTrashed();
        }
        if (User::hasRole(Auth::user(), 'company') || User::hasRole(Auth::user(), 'businessadmin')) {
            $query =  $query->where('id', Auth::user()->company_id);
        }
        $search = isset($filterArr['search']) ? $filterArr['search'] : '';
        if ($search) {
            $query = $query->where(function ($srcQuery) use ($search) {
                $srcQuery->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone_nr', 'LIKE', "%{$search}%");
            });
        }
        if (!$orderBy && !$order) {
            $orderBy = 'companies.id';
            $order = 'DESC';
        }
        $query->orderBy($orderBy, $order);
        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function plans()
    {
        $this->hasMany(Plan::class, 'user_id', 'id');
    }


    /**
     * Get the Active Subscription Plan.
     *
     * @return Plan|null
     */
    public function activePlan()
    {
        $subscription = $this->subscription('default');
        if ($subscription) {
            if ($subscription->cancelled() && !$subscription->onGracePeriod()) {
                return null;
            }

            $plan_id = $subscription->stripe_price;

            if (!empty($plan_id)) {
                return Plan::withTrashed()->where('stripe_plan_id', '=', $plan_id)->first();
            }

            return Plan::where('company_id', $this->id)->first();
        }

        return null;
    }

    /**
     * Get the Custom Subscription Plan.
     *
     * @return Plan|null
     */
    public function customPlan()
    {
        $plan = $this->activePlan();
        if ($plan) {
            $basePlan = $plan->basePlan;
            if ($basePlan && $basePlan->base_plan_id == null && $basePlan->stripe_addon_plan_id == null && $basePlan->stripe_product_id == null && $basePlan->user_id == $this->id) {
                return $basePlan;
            }
        }

        return null;
    }

    /**
     * get the active plan of the user
     *
     * @return object
     */
    public function getActivePlanAttribute()
    {
        return $this->activePlan() ?: freePlan();
    }

    public function getHasPaymentMethodAttribute()
    {
        return !empty($this->pm_last_four);
    }

    public function members()
    {
        return $this->user()->whereHas('role', function($q) {
            $q->where('name', User::ROLE_EMPLOYEE);
        });
    }

    public function company_admin()
    {
        return $this->user()->whereHas('role', function($q) {
            $q->where('name', User::ROLE_COMPANY);
        });
    }

    public function company_sub_admin()
    {
        return $this->user()->whereHas('role', function($q) {
            $q->where('name', User::ROLE_BUSINESSADMIN);
        });
    }
}
