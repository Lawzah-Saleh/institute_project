<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentDegreeController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        $degrees = DB::table('degrees')
            ->join('course_sessions', 'degrees.course_session_id', '=', 'course_sessions.id')
            ->join('courses', 'course_sessions.course_id', '=', 'courses.id')
            ->where('degrees.student_id', $student->id)
            ->select('degrees.*', 'courses.course_name')
            ->get();

        return view('dashboard-Student.degree', compact('degrees', 'student'));
    }
}
