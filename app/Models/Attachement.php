<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Attachement extends Model
{
    protected $fillable = [
        'filetype',
        'path',
        'subject_id',
        'created_at',
        'updated_at',
        'filename',
        'company_id',
        'form_id'
    ];

    protected static function booted()
    {
        static::deleting(function (Attachement $attachement) {
            $source = "company/$attachement->company_id/media/$attachement->filename";
            Storage::disk('local')->delete($source);
        });

        static::saving(function (Attachement $attachement){
           
            if ($attachement->isDirty('company_id') && ($attachement->getOriginal())){
            
                $oldComp = $attachement->original['company_id'];
                $newComp = $attachement->company_id;
                $source = "company/$oldComp/media/$attachement->filename";
                $destination = "company/$newComp/media/$attachement->filename";
                Storage::disk('local')->move($source, $destination);
            }
         

        });

    }

    public function scopeScpCompany($query){
        if (Auth::user()->role_id == 1){
            return $query;
        }
        return $query->where('company_id', Auth::user()->company_id);
    }

    public function getPathAttribute($value)
    {
        if (Str::startsWith($value, '/media/')) {
            return "/company/". $this->company_id . "/media/". $this->filename;
        }
        return $value;
    }
}
