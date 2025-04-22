<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'id',
    ];

    // identifikasi course di category mana
    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    // untuk cek course ini pertanyaannya apa aja
    public function questions(){
        return $this->hasMany(CourseQuestion::class, 'course_id', 'id');
    }

    // untuk cek course ini diambil oleh student mana aja
    public function students(){
        return $this->belongsToMany(User::class, 'course_students', 'course_id', 'user_id');
     }
}
