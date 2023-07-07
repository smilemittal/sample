<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class UserLearningPath extends Model
{
    use HasFactory;
    use SoftDeletes, Notifiable;

    protected $fillable = [
        'user_id', 'learning_group_id', 'company_id'
    ];
    protected $dates = ['archived_at'];
    protected $table = 'user_learning_paths';

    const DELETED_AT = 'archived_at';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function getLearningMember($learning_id)
    {
        return self::where('learning_group_id', $learning_id)->where('company_id', Auth::user()->company_id)->count();
    }
}
