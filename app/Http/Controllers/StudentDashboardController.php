<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course; 
use App\Models\CourseSessionStudent;
use App\Models\Attendance;
use App\Models\Student;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id(); // Get logged-in user ID
        
        // Retrieve student based on the logged-in user
        $student = Student::where('user_id', $userId)->first();
    
        if (!$student) {
            return redirect()->route('login')->with('error', 'لم يتم العثور على بيانات الطالب.');
        }
    
        $studentId = $student->id; // Get the correct student_id
    
        // Fetch the student's enrolled sessions, and then the associated courses
        $sessions = CourseSessionStudent::where('student_id', $studentId)
                                         ->with('course') // Correctly eager load the course relationship
                                         ->get();
    
        // Log the sessions to see if data is retrieved (updated to pass an array)
        \Log::info('Student Sessions:', ['sessions' => $sessions->toArray()]);
    
        $sessions = $sessions->map(function ($sessionStudent) {
            $course = $sessionStudent->course;
            return $course ? [
                'course_name' => $course->name,
                'session_id' => $sessionStudent->session_id,
                'progress' => 50, // Placeholder for progress
            ] : null;
        })->filter();
    
        // Check if courses are available (wrap TotalCourses in an array)
        $TotalCourses = $sessions->count();
        \Log::info('Total Courses:', ['total_courses' => $TotalCourses]);
    
        // Fetch attendance data
        $attendance = Attendance::where('student_id', $studentId)->get();
        $totalAttendance = $attendance->count();
        $attendedClasses = $attendance->where('status', 'present')->count();
        $attendancePercentage = $totalAttendance ? ($attendedClasses / $totalAttendance) * 100 : 0;
    
        // Log the attendance data
        \Log::info('Attendance Data:', ['totalAttendance' => $totalAttendance, 'attendedClasses' => $attendedClasses]);
    
        return view('dashboard-Student.dashboard', compact('sessions', 'attendancePercentage', 'TotalCourses'));
    }
    
    
}
