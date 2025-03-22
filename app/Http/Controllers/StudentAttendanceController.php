<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class StudentAttendanceController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        // Get all courses where student is enrolled in sessions
        $courseIds = DB::table('course_session_students')
            ->where('student_id', $student->id)
            ->pluck('course_session_id');

        $courses = DB::table('courses')
            ->join('course_sessions', 'courses.id', '=', 'course_sessions.course_id')
            ->whereIn('course_sessions.id', $courseIds)
            ->select('courses.id', 'courses.course_name')
            ->distinct()
            ->get();

        return view('dashboard-Student.attendance', compact('courses'));
    }
    public function getAttendanceData($course_id)
    {
        $student = Auth::user()->student;
    
        // Get course session IDs the student is enrolled in
        $courseSessionIds = DB::table('course_session_students')
            ->where('student_id', $student->id)
            ->pluck('course_session_id');
    
        if ($courseSessionIds->isEmpty()) {
            return response()->json([
                'totalDays' => 0,
                'attendanceDays' => 0,
                'absenceDays' => 0,
            ]);
        }
    
        // Get all course sessions related to this course
        $sessions = DB::table('course_sessions')
            ->whereIn('id', $courseSessionIds)
            ->where('course_id', $course_id)
            ->get(['id', 'daily_hours']);
    
        if ($sessions->isEmpty()) {
            return response()->json([
                'totalDays' => 0,
                'attendanceDays' => 0,
                'absenceDays' => 0,
            ]);
        }
    
        // Get total duration from courses table (in hours)
        $course = DB::table('courses')
            ->where('id', $course_id)
            ->first(['duration']);
    
        if (!$course) {
            return response()->json([
                'totalDays' => 0,
                'attendanceDays' => 0,
                'absenceDays' => 0,
            ]);
        }
    
        // Assume first session's daily hours (or take average if needed)
        $dailyHours = $sessions->first()->daily_hours ?? 2;
    
        // Calculate total course days
        $totalDays = ceil($course->duration / $dailyHours);
    
        $sessionIds = $sessions->pluck('id');
    
        $attendanceDays = DB::table('attendances')
            ->whereIn('session_id', $sessionIds)
            ->where('student_id', $student->id)
            ->where('status', '1') // حاضر
            ->count();
    
        $absenceDays = DB::table('attendances')
            ->whereIn('session_id', $sessionIds)
            ->where('student_id', $student->id)
            ->where('status', '0') // غائب
            ->count();
    
        return response()->json([
            'totalDays' => $totalDays,
            'attendanceDays' => $attendanceDays,
            'absenceDays' => $absenceDays,
        ]);
    }
    
    
}
