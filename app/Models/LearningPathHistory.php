<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LearningPathHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'form_id', 'user_id', 'type' , 'learning_path_id'
    ];

    public static function getLearningCounter($formId, $companyId, $learningPathId)
    {
        return  self::where('form_id', $formId)->where('company_id', $companyId)
        ->where('user_id', Auth::user()->id)
        ->where('learning_path_id', $learningPathId)
        ->count();
    }

    public function learningpath()
    {
        return $this->belongsTo(LearningPath::class, 'learning_path_id', 'id');
    }
    
    

    
}
