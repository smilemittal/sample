<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class LearningPath extends Model
{
    use HasFactory;
    use SoftDeletes, Notifiable;


    protected $guarded = ['id'];
    protected $dates = ['archived_at'];
    const DELETED_AT = 'archived_at';

    public function learningpath()
    {
        return $this->hasMany(LearningPathHistory::class);
    }

    public function members()
    {
        return $this->hasMany(UserLearningPath::class, 'learning_group_id', 'id');
    }

    public function forms()
    {
        return $this->hasMany(LearningPathForm::class, 'learning_group_id', 'id');
    }
}
