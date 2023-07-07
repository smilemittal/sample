<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    use SoftDeletes;
    protected  $guarded = ['id'];
    protected $dates = ['archived_at'];
    const DELETED_AT = 'archived_at';


    public static function boot()
    {
        parent::boot();

        self::deleting(function (Group $group) {
            // This callback is fired before the deleting of a contract
            $usrGrps = $group->members()->getModels();
            foreach ($usrGrps as $usrGrp) {
                $usrGrp->delete();
            }

            // $frms = $group->forms()->getModels();
            // foreach ($frms as $frm){
            //     $frm->deleted();
            //     $frm->save();
            // }

            // $frms = $group->forms()->getModels();
            // foreach ($frms as $frm){
            //     $frm->delete();
            //     $frm->save();
            // }


            // $attachement->projects()->delete() // Use a relationship for this, more eloquent ;)

        });
    }

    public function scopeScpCompany($query)
    {
        return User::hasRole(Auth::user(), 'superadmin') ?
            $query : $query->where('company_id', Auth::user()->company_id);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function members()
    {
        return $this->hasMany(UserGroup::class, 'group_id', 'id');
    }

    public function forms()
    {
        return $this->hasMany(GroupForm::class, 'group_id', 'id');
    }

    public static function getGroup($group_id)
    {
        $group = self::withTrashed()->where('id', $group_id)->where('company_id', Auth::user()->company_id)->first();
        return $group->name;
    }
}
