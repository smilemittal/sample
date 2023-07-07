<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Form;
use Illuminate\Support\Facades\Auth;

class AssignModuleUpdation extends Model
{
    use HasFactory;
    protected $fillable = [
        'form_id','description','company_id','comments','module_commented_at','status'
    ];
    public const PENDING = 'pending';
    public const UPDATE = 'update';

    public function assign_module()
    {
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }

    public static function checkIfModuleUpdation($formId)
    {
        $assignedModule =self::where('form_id', $formId)->where('company_id', Auth::user()->company_id)->count();
        $status = false;
        if ($assignedModule > 0) {
            $status = true;
        }
        return $status;
    }
}
