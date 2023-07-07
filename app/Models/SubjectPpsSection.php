<?php

namespace App\Models;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectPpsSection extends Model
{
    use HasFactory;
    protected $guarded = ['id',"updated_at", "created_at"
    ];
    protected $table = 'subject_pps_sections';

    public function ppsSectionDetail(){
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function section_detail(){
        return $this->belongsTo(PpsSection::class, 'section_id', 'id');
    }
}
