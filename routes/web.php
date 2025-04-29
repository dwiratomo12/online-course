<?php

use App\Http\Controllers\ProfileController;
use App\Models\Course;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\CourseQuestionController;
use App\Http\Controllers\CourseStudentController;
use App\Models\StudentAnswer;
use App\Http\Controllers\StudentAnswerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        //=================================== role teacher ================================
        Route::resource('courses', CourseController::class)
        ->middleware('role:teacher');

        // untuk teacher menambahkan course
        Route::get('/course/question/create/{course}', [CourseQuestionController::class, 'create'])
        ->middleware('role:teacher')
        ->name('course.create.question'); 

        // untuk teacher menyimpan course
        Route::post('/course/question/save/{course}', [CourseQuestionController::class, 'store'])
        ->middleware('role:teacher')
        ->name('course.create.question.store'); 

        Route::resource('course_question', CourseQuestionController::class)
        ->middleware('role:teacher');

        // untuk menampilkan teacher melihat student yang mengikuti course
        Route::get('course/students/show/{course}', [CourseStudentController::class, 'index'])
        ->middleware('role:teacher')
        ->name('course.course_students.index'); 

        // untuk membuat teacher supaya student bisa mengikuti course
        Route::post('course/students/create/{course}', [CourseStudentController::class, 'create'])
        ->middleware('role:teacher')
        ->name('course.course_students.create'); 

        // untuk menyimpan teacher supaya student bisa mengikuti course
        Route::post('course/students/create/save/{course}', [CourseStudentController::class, 'store'])
        ->middleware('role:teacher')
        ->name('course.course_students.store'); 

        //=================================== role student ================================
        //menampilkan beberapa kelas yang diberikan oleh guru
        Route::get('/learning', [LearningController::class, 'index'])
        ->middleware('role:student')
        ->name('learning.index');

        Route::get('/learning/finished/{course}', [LearningController::class, 'learning_finished'])
        ->middleware('role:student')
        ->name('learning.finished.course');

        // untuk menampilkan raport progress belajar
        Route::get('/learning/rapport/{course}', [LearningController::class, 'learning_rapport'])
        ->middleware('role:student')
        ->name('learning.rapport.course');

        // untuk student bisa melihat pertanyaan
        Route::get('/learning/{course}/{question}', [LearningController::class, 'learning'])
        ->middleware('role:student')
        ->name('learning.course');

        Route::get('/learning/{course}/{question}', [StudentAnswerController::class, 'store'])
        ->middleware('role:student')
        ->name('learning.course.answer.store');


    });
});

require __DIR__ . '/auth.php';
