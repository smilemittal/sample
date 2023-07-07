<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Directory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'parent_id', 'company_id'
    ];

    public function directoryModules()
    {
        return  $this->hasMany(DirectoryModule::class, 'directory_id', 'id');
    }

    public function form_description()
    {
        return $this->hasMany(CompanyForm::class, 'directory', 'id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    public static function getNoOfFolder($directoryId, $companyId)
    {
        return  self::where('parent_id', $directoryId)->where('company_id', $companyId)->count();
        
    }

    public static function getOneLevel($catId) {
        $query=self::where('parent_id', $catId)->where('company_id', Auth::user()->company_id)->get();
        $catIdArr = array();
        foreach ($query as $data) {
            $catIdArr []=$data->id;
        }
        return $catIdArr ;
    }
    
    public static function getChildren($parentId, &$tree_string=array()) {
        $tree = array();
        $tree = self::getOneLevel($parentId);
        if (count($tree)>0 && is_array($tree)) {
            $tree_string=array_merge($tree_string,$tree);
        }
        foreach ($tree as $val) {
            self::getChildren($val, $tree_string);
        }
        return $tree_string;
    }
}
