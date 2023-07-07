<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;


class ActionLog extends Model
{
    public function scopeScpCompany($query)
    {

        return User::hasRole(Auth::user(), 'superadmin') ? $query : $query->whereHas('user', function ($result) {
            $result->where('company_id', Auth::user()->company_id);
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // public static function logActionFor($model = null, $subject, $destination = null, $userNote = null, $support = null)
    // {
    //     $actionLog = new ActionLog;
    //     $actionLog->model_name = !empty($model) ? class_basename($model) : NULL;
    //     $actionLog->model_id = !empty($model->id) ? $model->id : NULL;
    //     $actionLog->user_id = Auth::user()->id;
    //     $actionLog->json = !empty($model) && is_object($model) ? json_encode($model->getAttributes()) : NULL;
    //     $actionLog->system_note = $destination;
    //     $actionLog->user_note = $userNote;
    //     $actionLog->subject = $subject;
    //     $actionLog->ip_address = Request::getClientIp();
    //     $actionLog->browser = Request::header('User-Agent');
    //     $actionLog->is_support = $support;
    //     $actionLog->save();
    // }
}
