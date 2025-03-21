<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseSessionStudent;
use App\Models\Attendance;
use App\Models\Student;


use App\Models\CourseSession;

class StudentDashboardController extends Controller
{
    public function index()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->hasRole('student')) {
            // Fetch student record (assuming a one-to-one relationship between User and Student)
            $student = $user->student;

            // if (!$student) {
            //     return redirect()->route('home')->with('error', 'Student profile not found.');
            // }

            // Count distinct course sessions the student is enrolled in
            $totalCourses = DB::table('course_session_students')
                ->where('student_id', $student->id)
                ->distinct()
                ->count('course_session_id');

            return view('dashboard-Student.dashboard', compact('totalCourses'));
        }

        abort(403, 'Unauthorized action.');
    }




}
