<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyForm extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'company_forms';
    protected $dates = ['archived_at'];
    const DELETED_AT = 'archived_at';

    public const NEW = 'new';
    public const UPDATE = 'update';
    public const SEND_REQUSET_TO_ADMIN = 'admin request';



    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }

    public function directory()
    {
        return $this->belongsTo(Directory::class, 'directory_id', 'id');
    }
}
