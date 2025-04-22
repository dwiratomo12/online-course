<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseAnswer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'id',
    ];

    //untuk cek jawaban course ada di pertanyaan mana
    public function question(){
        return $this->belongsTo(CourseQuestion::class, 'question_id');
    }
}
