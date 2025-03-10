<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CourseSession;
use App\Models\Employee;
use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id(); // ✅ Get logged-in user ID

        // ✅ Retrieve employee ID based on the logged-in user
        $employee = Employee::where('user_id', $userId)->first();

        if (!$employee) {
            return redirect()->route('login')->with('error', 'لم يتم العثور على بيانات الموظف.');
        }

        $employeeId = $employee->id; // ✅ Get the correct `employee_id`

        // ✅ Fetch sessions for this employee
        $sessionIds = CourseSession::where('employee_id', $employeeId)->pluck('id');

        // ✅ Count distinct students enrolled in these sessions
        $students_count = DB::table('course_session_students')
                            ->whereIn('session_id', $sessionIds)
                            ->distinct()
                            ->count('student_id');

        // ✅ Count unique courses taught by this employee
        $courses_count = CourseSession::where('employee_id', $employeeId)
                                     ->distinct()
                                     ->count('course_id');

        // ✅ Ensure courses_count is always defined (default to 0 if empty)
        if (!isset($courses_count)) {
            $courses_count = 0;
        }

        return view('Teacher-dashboard.dashboard', [
            'students_count' => $students_count,
            'courses_count' => $courses_count, // ✅ Now correctly passed to the view
        ]);
    }
    public function getTeacherSessions(Request $request)
{
    $teacherId = auth()->id(); // جلب ID المدرّس المسجل الدخول

    // البحث عن الموظف المرتبط بهذا المستخدم
    $employee = Employee::where('user_id', $teacherId)->first();
    if (!$employee) {
        return response()->json([]);
    }

    // جلب الجلسات الخاصة بالمدرّس
    $sessions = CourseSession::where('employee_id', $employee->id)
        ->whereDate('start_date', '>=', now())
        ->get();

    // تنسيق البيانات لتناسب FullCalendar
    $events = [];
    foreach ($sessions as $session) {
        $events[] = [
            'title' => '📚 ' . $session->course->name,
            'start' => $session->start_date . 'T' . $session->start_time,
            'end'   => $session->end_date . 'T' . $session->end_time,
            'color' => '#007bff' // لون الجلسة
        ];
    }

    return response()->json($events);
}
}
