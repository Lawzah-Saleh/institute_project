<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\Student;
use App\Models\Employee;

class AdminAttendanceController extends Controller
{
    // 🟢 عرض تقارير الحضور
    public function index(Request $request)
    {
        $query = Attendance::query()->with(['student', 'session.course', 'employee']);

        // 🔍 تصفية الحضور بناءً على البحث
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->filled('session_id')) {
            $query->where('session_id', $request->session_id);
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->paginate(10);
        $students = Student::all();
        $sessions = CourseSession::all();
        $teachers = Employee::where('emptype', 'teacher')->get();

        return view('admin.pages.attendance.index', compact('attendances', 'students', 'sessions', 'teachers'));
    }

    // 🟢 تعديل حالة الحضور
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->status = $request->status;
        $attendance->save();

        return redirect()->back()->with('success', 'تم تحديث حالة الحضور بنجاح.');
    }
}




// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Attendance;
// use App\Models\Student;
// use App\Models\CourseSession;
// use Illuminate\Support\Facades\Auth;

// class AttendanceController extends Controller
// {
//     // 🟢 عرض الجلسات التي يدرسها المدرس
//     public function getTeacherSessions()
//     {
//         $teacher = Auth::user()->employee; // جلب بيانات المدرس
//         if (!$teacher) {
//             return redirect()->back()->with('error', 'لم يتم العثور على بيانات المدرس.');
//         }

//         $sessions = CourseSession::where('employee_id', $teacher->id)
//             ->whereDate('start_date', '>=', now())
//             ->get();

//         return view('Teacher-dashboard.attendance.sessions', compact('sessions'));
//     }

//     // 🟢 عرض الطلاب المسجلين في الجلسة
//     public function getSessionAttendance($sessionId)
//     {
//         $session = CourseSession::findOrFail($sessionId);
//         $students = $session->students;

//         return view('Teacher-dashboard.attendance.mark', compact('session', 'students'));
//     }

//     // 🟢 تسجيل الحضور
//     public function markAttendance(Request $request)
//     {
//         $request->validate([
//             'session_id' => 'required|exists:course_sessions,id',
//             'attendances' => 'required|array',
//             'attendances.*.student_id' => 'required|exists:students,id',
//             'attendances.*.status' => 'required|in:0,1', // 1: حاضر, 0: غائب
//         ]);

//         foreach ($request->attendances as $attendanceData) {
//             Attendance::updateOrCreate(
//                 [
//                     'student_id' => $attendanceData['student_id'],
//                     'session_id' => $request->session_id,
//                 ],
//                 [
//                     'employee_id' => Auth::user()->employee->id,
//                     'attendance_date' => now(),
//                     'status' => $attendanceData['status'],
//                 ]
//             );
//         }

//         return redirect()->back()->with('success', 'تم تسجيل الحضور بنجاح.');
//     }
    
// }
