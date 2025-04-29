<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'id',
    ];

    //untuk cek pertanyaan course ini untuk di course mana
    public function course(){
        return $this->belongsTo(Course::class, 'course_id');
    }

    //untuk cek pertanyaan course ini jawabannya apa aja
    public function answers(){
        return $this->hasMany(CourseAnswer::class, 'course_question_id', 'id');
    }
}
