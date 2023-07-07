<?php

namespace App\Models;

use App\Models\UserForm;
use App\Models\LearningPathForm;
use App\Models\DirectoryModule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Models\FormTimeDescription;

class Form extends Model
{
    use SoftDeletes, Notifiable;
    protected $guarded = ['id'];
    public const ACTIVE = 1;

    public const IN_ACTIVE = 0;

    protected $dates = ['archived_at'];
    const DELETED_AT = 'archived_at';

    protected static function booted()
    {
        // static::created(function ($subject) {
        //     //
        // });

        static::deleting(function (Form $form) {
            // DELETE ALL FILE ATTACHEMENTS
            $files = $form->attachements()->getModels();
            if ($files) {
                foreach ($files as $file) {
                    $file->delete();
                }
            }
            // DELETE ALL RESPONSES
            $responses = $form->responses()->getModels();
            if ($responses) {
                foreach ($responses as $response) {
                    $response->delete();
                }
            }

            // DELETE ALL FORM GROUPS
            // $formGroups = $form->groups()->getModels();
            // foreach ($formGroups as $formGroup) {
            //     $formGroup->delete();
            // }
        });

        static::saving(function (Form $form) {

            if ($form->isDirty('company_id')) {
                $files = $form->attachements()->getModels();
                if ($files) {
                    // 
                    foreach ($files as $file) {
                        $file->company_id = $form->company_id;
                        $file->save();
                    }
                }
            }
        });
    }


    public function scopeScpCompany($query)
    {

        return User::hasRole(Auth::user(), 'superadmin') ? $query : $query->whereHas('companies', function ($q) {
            $q->where('company_forms.company_id', Auth::user()->company_id)->whereNull('company_forms.archived_at');
        });
    }
    /*
        Log::debug($this->role);
        Log::debug($this->hasRole('company'));
    */

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function attachements()
    {
        return $this->hasMany(Attachement::class, 'form_id', 'id');
    }

    public function responses()
    {
        return $this->hasMany(Response::class, 'typeform_id', 'typeform_id')->orderBy('created_at', 'desc');
    }

    public function groups()
    {
        return $this->hasMany(GroupForm::class, 'form_id', 'id');
    }

    public function learningpath()
    {
        return $this->hasMany(LearningPathForm::class, 'form_id', 'id');
    }

    public function assign_module()
    {
        return $this->hasMany(AssignModuleUpdation::class, 'form_id', 'id');
    }

    public function directoryModules()
    {
        return $this->hasMany(DirectoryModule::class, 'form_id', 'id');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_forms', 'form_id', 'company_id');
    }

    public function form_company()
    {
        return $this->hasMany(CompanyForm::class, 'form_id', 'id');
    }


    public function user_form()
    {
        return $this->hasMany(UserForm::class, 'form_id', 'id');
    }

    public function form_time_detail()
    {
        return $this->hasMany(FormTimeDescription::class, 'form_id', 'id');
    }
}
