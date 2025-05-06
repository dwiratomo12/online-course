<?php

namespace App\Http\Controllers;

use App\Models\CourseQuestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\StudentAnswer;
use App\Models\Course;

class LearningController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $my_courses = $user->courses()->with('category')->orderBy('id', 'desc')->get();

        // untuk mendapatkan pertanyaan yang sudah dijawab
        foreach ($my_courses as $course) {
            $totalQuestionCount = $course->questions()->count();

            $asnweredQuestionCount = StudentAnswer::where('user_id', $user->id)
            ->whereHas('question', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->distinct('')->count('course_question_id');

            // untuk mendapatkan pertanyaan yang belum dijawab
            if($asnweredQuestionCount < $totalQuestionCount){
                $firstUnansweredQuestion = CourseQuestion::where('course_id', $course->id)
                ->whereNotIn('id', function ($query) use ($user, $course) {
                    $query->select('course_question_id')
                    ->from('student_answers')
                    ->where('user_id', $user->id);
                })->orderBy('id','asc')->first();

                // 10, 2, 3 ... 10 ... null
                $course->nextQuestionId = $firstUnansweredQuestion ? $firstUnansweredQuestion->id : null;
                //     ->whereHas('question', function ($query) use ($course) {
                //         $query->where('course_id', $course->id);
                //     });
                // })->first();
            }
            else {
                $course->nextQuestionId = null;
            }
        }
        
        return view('student.courses.index', [
            'my_courses' => $my_courses,
        ]);
    }

    public function learning(Course $course, $question)
    {
        $user = Auth::user();

        //cek apakah course ini diambil oleh student
        $isEnrolled = $user->courses()->where('user_id', $user->id)->exists();
        if (!$isEnrolled) {
            abort(404);
        }

        //cek sudah berapa banyak pertanyaan yang sudah dijawab, cari pertanyaan dulu baru kita tampilkan
        $currentQuestion = CourseQuestion::where('course_id', $course->id)->where('id', $question)->firstOrFail();

        return view('student.courses.learning', [
            'course' => $course,
            'currentQuestion' => $currentQuestion,
        ]);
    }
}
