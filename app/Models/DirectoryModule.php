<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryModule extends Model
{
    use HasFactory;
    protected $fillable = [
        'directory_id', 'form_id', 'company_id',
    ];

    public static function getNoOfModuleAssignedToFolder($directoryId, $companyId)
    {
        return self::where('directory_id', $directoryId)->where('company_id', $companyId)->count();
    }

    public function directoryModules()
    {
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }

    public function directory()
    {
        return $this->belongsTo(Directory::class, 'directory_id', 'id');
    }
}
