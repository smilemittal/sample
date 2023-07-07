<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ReviewForm extends Model
{
    use HasFactory;
    use SoftDeletes, Notifiable;


    protected $guarded = ['id'];
    protected $dates = ['archived_at'];
    protected $table = 'review_forms';
    const DELETED_AT = 'archived_at';

    public const NEW = 'new';
    public const IN_PROCESS = 'in-process';
    public const CLOSED = 'closed';




   
}