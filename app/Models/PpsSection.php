<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubjectPpsSection;

class PpsSection extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'sub_heading', 'display_order' , 'status','is_optional'
    ];

    public function section_detail(){
        return $this->hasMany(SubjectPpsSection::class, 'section_id', 'id');
    }

}
