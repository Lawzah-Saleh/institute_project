<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\CourseSession;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * إظهار الجلسات التي يقوم المدرس بتدريسها.
     */
    public function getTeacherSessions()
    {
        $employeeId = Auth::user()->employee->id; // الحصول على ID الموظف المرتبط بالمستخدم

        $sessions = CourseSession::where('teacher_id', $employeeId)
            ->with('students') // جلب الطلاب المسجلين في الجلسة
            ->get();

        return response()->json($sessions);
    }

    /**
     * تسجيل حضور الطلاب.
     */
    public function markAttendance(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:course_sessions,id',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|boolean',
        ]);

        $employeeId = Auth::user()->employee->id; // جلب ID المدرس من الموظفين

        foreach ($request->attendances as $attendanceData) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $attendanceData['student_id'],
                    'session_id' => $request->session_id,
                ],
                [
                    'employee_id' => $employeeId, // تسجيل الموظف الذي قام بالحضور
                    'status' => $attendanceData['status'],
                    'attendance_date' => now(),
                ]
            );
        }

        return response()->json(['message' => 'تم تسجيل الحضور بنجاح']);
    }

    /**
     * عرض الحضور لجلسة معينة.
     */
    public function getSessionAttendance($sessionId)
    {
        $attendance = Attendance::where('session_id', $sessionId)
            ->with(['student', 'employee'])
            ->get();

        return response()->json($attendance);
    }
}
