<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Role extends Model
{
    use HasFactory;
    // use SoftDeletes, Notifiable;
    protected $table= 'roles';

    public function users()
	{
		return $this->hasMany(User::class, 'role_id', 'id');
	}
   
}
