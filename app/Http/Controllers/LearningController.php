<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $my_courses = $user->courses()->with('category')->orderBy('id', 'desc')->get();
        
        return view('student.courses.index', [
            'my_courses' => $my_courses,
        ]);
    }
}
