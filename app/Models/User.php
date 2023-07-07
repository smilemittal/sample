<?php

namespace App\Models;

use App\Models\Company;
use App\Models\UserGroup;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $guarded = ['id'];
    protected $dates = ['archived_at'];
    const DELETED_AT = 'archived_at';
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public const ROLE_SUPERADMIN = 'superadmin';
    public const ROLE_SITEADMIN = 'siteadmin';
    public const ROLE_COMPANY = 'company';
    public const ROLE_EMPLOYEE = 'employee';
    public const ROLE_BUSINESSADMIN = 'businessadmin';

    public static function boot()
    {

        /**
         * Delete this user from UserGroup when this user is deleted
         */

        parent::boot();

        self::deleting(function (User $usr) {
            // This callback is fired before the deleting of a contract

            // Delete all UserGroup entries for this user
            $groups = $usr->groups()->getModels();
            foreach ($groups as $group) {
                $group->delete();
            }
        });
    }


    public static function create($data)
    {
        $model = static::query()->create($data);
        $model->save();
        return $model;
    }

    public function save(array $options = [])
    {

        /*
            When a new form is saved assign the current users company_id to the owner_id of the xme_form

            1. Check if the current user is a company
            2. If he is a company we want to assign his company id to the new user

        */

        // 1. Check if the current user is a company



        // TCG\Voyager\Models\User


        // If no owner has been assigned,
        // assign the current user's id as the owner of the workstation
        // if (!$this->owner_id && Auth::user()) 
        // {
        //     $this->owner_id = Auth::user()->getKey();
        // }
        $this->name = implode(' ', [$this->firstname, $this->lastname]);
        return parent::save();
    }

    public function scopeScpCompany($query)
    {
        return  self::hasRole(Auth::user(), 'superadmin') ? $query :
            $query->where('company_id', Auth::user()->company_id);
    }


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function groups()
    {
        return $this->hasMany(UserGroup::class, 'user_id', 'id');
    }

    public function learningpath()
    {
        return $this->hasMany(UserLearningPath::class, 'user_id', 'id');
    }


    public function user_forms()
    {
        return $this->hasMany(UserForm::class, 'user_id', 'id');
    }

    public static function hasRole($loggedUser, $userRole)
    {
        $status = false;
        ($loggedUser->role->name == $userRole) ? $status = true : $status = false;
        return $status;
    }

    public function list(
        $filterArr = false,
        $fields = false,
        $orderBy = false,
        $order = false,
        $isArchived = false,
        $perPage = false,
    ) {
        if (!$fields) {
            $fields = ['users.*'];
        }
        $query = User::scpCompany()->select($fields);
        if ($isArchived) {
            $query = $query->onlyTrashed();
        }
        $query = $query->join('companies', 'companies.id', '=', 'users.company_id')
            ->join('roles', 'roles.id', '=', 'users.role_id');


        $search = isset($filterArr['search']) ? $filterArr['search'] : '';
        if ($search) {
            $query = $query->where(function ($usrQuery) use ($search) {
                $usrQuery
                    ->where('users.name', 'LIKE', "%{$search}%")
                    ->orWhere('users.email', 'LIKE', "%{$search}%")
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('role', function ($q) use ($search) {
                        $q->where('display_name', 'LIKE', "%{$search}%");
                    });
            });
        }
        if (!$orderBy && !$order) {
            $orderBy = 'users.id';
            $order = 'DESC';
        }
        $query->orderBy($orderBy, $order);
        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function canImpersonate()
    {
        return $this->role->name == self::ROLE_SUPERADMIN || $this->role->name == self::ROLE_SITEADMIN;
    }

    public function canBeImpersonated()
    {
        return $this->role->name != self::ROLE_SUPERADMIN;
    }

    public function isImpersonated()
    {
        $token = $this->currentAccessToken();
        return $token->name == 'IMPERSONATION token';
    }
}
