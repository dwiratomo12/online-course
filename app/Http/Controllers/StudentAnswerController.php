<?php

namespace App\Http\Controllers;

use App\Models\CourseQuestion;
use App\Models\StudentAnswer;
use App\Models\Course;
use App\Models\CourseAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class StudentAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course, $question)
    {
        
        $question_details = CourseQuestion::where('id', $question)->first();

        $validated = $request->validate([
            'answer_id' => 'required|exists:course_answers,id',
        ]);

        DB::beginTransaction();

        try{
            $selectedAnswer = CourseAnswer::find($validated['answer_id']);

            // Check if the selected answer belongs to the question
            if($selectedAnswer->course_question_id != $question){
                $error = ValidationException::withMessages([
                    'system_error' => ['System error!' .['Jawaban tidak tersedia dengan pertanyaan']],
                ]);

                throw $error;
            }
            // Check if the question is already answered
            $existingAnswer = StudentAnswer::where('user_id', Auth::id())
                ->where('course_question_id', $question)
                ->first();
            if ($existingAnswer) {
                $error = ValidationException::withMessages([
                    'system_error' => ['System error!' .['Kamu telah menjawab pertanyaan ini sebelumnya']],
                ]);

                throw $error;
            }

            $answerValue = $selectedAnswer->is_correct ? 'correct' : 'wrong';
            StudentAnswer::create([
                'user_id' => Auth::id(),
                'course_question_id' => $question,
                'answer' => $answerValue,
            ]);
            
            DB::commit();

            $nextQuestion = CourseQuestion::where('course_id', $course->id)
                ->where('id', '>', $question)
                ->orderBy('id','asc')
                ->first();

            if($nextQuestion){
                return redirect()->route('dashboard.learning.course', ['course' => $course->id, 'question' => $nextQuestion->id])
                ->with('success', 'Jawaban kamu sudah disimpan, silahkan lanjut ke pertanyaan selanjutnya');
            } else {
                return redirect()->route('dashboard.learning.finished.course', ['course' => $course->id])
                ->with('success', 'Jawaban kamu sudah disimpan, silahkan lihat raport belajar kamu');
            }

            
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'system_error' => ['System error!' . $e->getMessage()],
            ]);

            throw $error;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentAnswer $studentAnswer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentAnswer $studentAnswer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentAnswer $studentAnswer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentAnswer $studentAnswer)
    {
        //
    }
}
