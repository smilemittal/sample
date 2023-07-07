<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class QrCode extends Model
{
    protected $fillable = [
        'qrid',
        'target_url',
        'status',
        'display_name',
        'typeform_id',
        'user_id',
        'company_id',
        'department_id',
        'svg'
    ];

    public function scopeScpCompany($query)
    {
        // return Auth::user()->hasRole('superadmin') ? $query : $query->where('company_id', Auth::user()->company_id);
        return Auth::user()->hasRole('superadmin') ? $query : $query->where('company_id', Auth::user()->company_id);
        
    }
}
