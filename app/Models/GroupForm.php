<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class GroupForm extends Model
{
    use SoftDeletes;
    protected $dates = ['archived_at'];
    const DELETED_AT = 'archived_at';
    protected $tables = 'group_forms';
    
    protected $fillable = [
        'form_id',
        'group_id',
        'is_reassign', 
        'reassign_interval',
        'custom_interval',
        'month_reassign_day',
        'week_reassign_day',
        'reassign_time',
        'end_point', 'end_type', 'end_times', 'end_date', 'pending_end_times'
    ];

    public const WEEKDAYS = [
        7 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'company_id', 'id');
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id', 'id')->withTrashed();
    }

    public function comapny_form()
    {
        return $this->belongsTo(CompanyForm::class, 'company_form_id', 'id');
    }
    
    public function actualGroup()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
