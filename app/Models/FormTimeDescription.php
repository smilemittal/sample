<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Form;


class FormTimeDescription extends Model
{
    use HasFactory;
    protected $fillable = [
        'form_id', 'start_time', 'end_time' , 'description'
    ];
    protected $table = 'form_time_descriptions';


    public function form_time_detail()
    {
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }
}
