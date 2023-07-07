<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingHistory extends Model
{
    use HasFactory;

    protected $table = 'training_histories';

    protected $fillable = ['user_id', 'form_id', 'group_id', 'status',
    'completed_date', 'assigned_date', 'expiry_date'];

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id', 'id')->withTrashed();
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

}
