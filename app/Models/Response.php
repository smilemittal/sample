<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Response extends Model
{
    protected $fillable = [
        'datetime_added',
        'time_requested',
        'email',
        'name',
        'json',
        'typeform_id',
        'response_id',
        'submitted_at',
        
    ];

    protected $dates = [
        'submitted_at',
        'datetime_added',
        'time_requested',
    ];

    protected $casts = [ 'submitted_at'=>'datetime'];


    protected static function booted()
    {

        static::deleting(function (Response $response) {
        
        });
    }

    public function scopeScpCompany($query)
    {
        return $query->whereHas('xmeForm.form_company', function ($result) {
            $result->where('company_id', Auth::user()->company_id);
        });
    }

    public function xmeForm()
    {
        return $this->belongsTo(Form::class, 'typeform_id', 'typeform_id');
    }
}
