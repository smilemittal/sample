<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserGroup extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $dates = ['archived_at'];
    const DELETED_AT = 'archived_at';

    public function group()
    {
        return $this->belongsTo(Group::class, 'company_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function actualGroup()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
