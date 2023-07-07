<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Form;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class LearningPathForm extends Model
{
    use HasFactory;
    use SoftDeletes, Notifiable;
    protected $fillable = [
        'form_id','learning_group_id','display_order','company_id'
    ];
    protected $dates = ['archived_at'];
    const DELETED_AT = 'archived_at';
    
    public function scopeScpCompany($query)
    {
        return isSuperAdmin() ? $query : $query->where('company_id', Auth::user()->company_id);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    //  public function learningpath()
    // {
    //     return $this->belongsTo(Group::class, 'company_id', 'id');
    // }

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id', 'id')->withTrashed();
    }

    public static function getLearningModule($learning_id){
        return self::where('learning_group_id', $learning_id)->where('company_id', Auth::user()->company_id)->count();
    }

}
