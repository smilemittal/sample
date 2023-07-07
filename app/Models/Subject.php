<?php

namespace App\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\SubjectPpsSection;

class STATUS_subject
{
    public const BACKBURNER = 1;
    public const DRAFT = 0;
    public const DEVELOP = 100;
    public const READY = 101;
    public const DISMISSED = 102;
    public const SUBMITTED = 103;
    public const REOPEN = 104;
    public const IDENTIFY_REASON = 'REVIEW_UPDATE';

    public static function statusStringFor($code)
    {
        switch ($code) {
            case STATUS_subject::DRAFT:
                return 'draft';

            case STATUS_subject::BACKBURNER:
                return 'backburner';

            case STATUS_subject::DEVELOP:
                return 'incomplete';

            case STATUS_subject::READY:
                return 'approved';

            case STATUS_subject::DISMISSED:
                return 'dismissed';

            case STATUS_subject::SUBMITTED:
                return 'submitted';
            case STATUS_subject::REOPEN:
                return 'reopen';
            default:
                return 'err403';
        }
    }
}

class PRIORITY_subject
{
    public const LOW = 0;
    public const MEDIUM = 1;
    public const HIGH = 2;

    public static function priorityStringFor($code)
    {
        switch ($code) {
            case PRIORITY_subject::LOW:
                return 'low';

            case PRIORITY_subject::MEDIUM:
                return 'medium';

            case PRIORITY_subject::HIGH:
                return 'high';

            default:
                # code...
                break;
        }
    }
}

class Subject extends Model
{
    use SoftDeletes;

    public const BACKBURNER = 1;
    public const DRAFT = 0;
    public const DEVELOP = 100;
    public const REVIEW_UPDATE = 'REVIEW_UPDATE';


    protected $guarded = ['id'];

    protected $dates = ['archived_at'];
    const DELETED_AT = 'archived_at';

    public static function boot()
    {
        parent::boot();

        self::deleting(function (Subject $subject) {
            // This callback is fired before the deleting of a contract
            $attachements = $subject->attachements()->getModels();
            foreach ($attachements as $attach) {
                $attach->delete();
            }

            $actions = $subject->actionLogs()->getModels();
            foreach ($actions as $action) {
                $action->delete();
            }
            // $attachement->projects()->delete() // Use a relationship for this, more eloquent ;)

        });
    }

    protected static function booted()
    {
        static::saving(function (Subject $subject) {

            if ($subject->isDirty('company_id')) {
                $files = $subject->attachements()->getModels();
                if ($files) {
                    //
                    foreach ($files as $file) {
                        $file->company_id = $subject->company_id;
                        $file->save();
                    }
                }
            }
        });
    }

    /*
        LOCAL SCOPES
    */

    public function scopeScpCompany($query)
    {

        if (Auth::user()->role_id == 5) {
            return $query->where('author', Auth::user()->id);
        }

        return $query->where('company_id', Auth::user()->company_id);
    }

    public function scopeScpIdentify($query)
    {
        return $query->where('status', '<', '100');
    }

    public function scopeScpDevelop($query)
    {
        return $query->where('status', '>=', '100');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }


    public function lastEditor()
    {
        return $this->belongsTo(User::class, 'last_edit_by', 'id');
    }

    // public function lastActioneer()
    // {
    //     return $this->belongsTo(User::class, 'action_by', 'id');
    // }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function attachements()
    {
        return $this->hasMany(Attachement::class, 'subject_id', 'id');
    }

    public function actionLogs()
    {
        return $this->hasMany(ActionLog::class, 'model_id', 'id')->where('model_name', class_basename($this));
    }


    public function currentAction()
    {

        return $this->hasOne(ActionLog::class, 'model_id', 'id')->where('model_name', "Subject")->orderBy('id', 'desc');
    }

    public static function checkIfModuleUpdate($form_id)
    {
        $is_update = self::where('form_id', $form_id)->where('company_id', Auth::user()->company_id)->first();
        if ($is_update) {
            return true;
        }
    }

    public function ppsSectionDeatil()
    {
        return $this->hasMany(SubjectPpsSection::class, 'subject_id', 'id');
    }
}
