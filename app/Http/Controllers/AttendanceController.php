<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\Attendance;
use App\Models\Student;

class AttendanceController extends Controller
{
    // 🔹 عرض صفحة إدارة الحضور
    public function index(Request $request)
    {
        $departments = Department::all(); 
        $courses = Course::all();


        $query = Attendance::query();

        // 🔹 تصفية حسب القسم
        if ($request->filled('department_id')) {
            $query->whereHas('session.course', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // 🔹 تصفية حسب الكورس
        if ($request->filled('course_id')) {
            $query->whereHas('session', function ($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }

        // 🔹 تصفية حسب الجلسة
        if ($request->filled('session_id')) {
            $query->where('session_id', $request->session_id);
        }

        // 🔹 تصفية حسب الحالة (حاضر أو غائب)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🔹 تصفية حسب اسم الطالب
        if ($request->filled('student_name')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('student_name_ar', 'LIKE', "%{$request->student_name}%")
                  ->orWhere('student_name_en', 'LIKE', "%{$request->student_name}%");
            });
        }
                // 🔹 تصفية حسب اسم الكورس
                if ($request->filled('course_name')) {
                    $query->whereHas('session.course', function ($q) use ($request) {
                        $q->where('course_name', 'LIKE', "%{$request->course_name}%");
                    });
                }

        $attendances = $query->with(['student', 'session.course'])->get();

        return view('admin.pages.attendance.index', compact('departments','courses', 'attendances'));
    }

    // 🔹 تغيير حالة الحضور
    public function toggleAttendance($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->status = !$attendance->status;
        $attendance->save();

        return redirect()->route('admin.attendance.index')->with('success', 'تم تحديث حالة الحضور بنجاح.');
    }

    // 🔹 إحضار الكورسات بناءً على القسم المختار
    public function getCoursesByDepartment($departmentId)
    {
        $courses = Course::where('department_id', $departmentId)->get();
        return response()->json($courses);
    }

    // 🔹 إحضار الجلسات بناءً على الكورس المختار
    public function getSessionsByCourse($courseId)
    {
        $sessions = CourseSession::where('course_id', $courseId)->get();
        return response()->json($sessions);
    }
    public function store(Request $request)
{
    $validated = $request->validate([
        'student_id' => 'required|exists:students,id',
        'session_id' => 'required|exists:course_sessions,id',
        'status' => 'required|boolean',
    ]);

    Attendance::create([
        'student_id' => $request->student_id,
        'session_id' => $request->session_id,
        'attendance_date' => now(),
        'status' => $request->status,
    ]);

    return redirect()->back()->with('success', 'تم تسجيل الحضور بنجاح.');
}
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'status' => 'required|boolean',
    ]);

    $attendance = Attendance::findOrFail($id);
    $attendance->update(['status' => $request->status]);

    return redirect()->back()->with('success', 'تم تعديل الحضور بنجاح.');
}

public function destroy($id)
{
    Attendance::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'تم حذف الحضور بنجاح.');
}
public function report(Request $request)
{
    // جلب الأقسام من قاعدة البيانات
    $departments = Department::all();

    // جلب قيم الطلب من النموذج
    $departmentId = $request->input('department_id');
    $courseId = $request->input('course_id');
    $sessionId = $request->input('session_id');

    // جلب الكورسات بناءً على القسم المحدد
    $courses = Course::where('department_id', $departmentId)->get();

    // جلب الجلسات بناءً على الكورس المحدد
    $sessions = CourseSession::where('course_id', $courseId)->get();

    // جلب بيانات الحضور بناءً على الجلسات المحددة
    $attendanceData = [];
    foreach ($sessions as $session) {
        $presentCount = Attendance::where('session_id', $session->id)
                                  ->where('status', true)
                                  ->count();
        $absentCount = Attendance::where('session_id', $session->id)
                                 ->where('status', false)
                                 ->count();

        $attendanceData[] = [
            'session' => $session->session_name,
            'present' => $presentCount,
            'absent' => $absentCount
        ];
    }

    // إرجاع البيانات إلى الـ View
    return view('admin.pages.attendance.report', compact('attendanceData', 'courses', 'departments', 'sessions', 'departmentId', 'courseId', 'sessionId'));
}





public function monthlyAttendanceReport(Request $request)
{
    // الحصول على الشهر والسنة المطلوبين، وإذا لم يُحدد، يتم استخدام الشهر الحالي
    $month = $request->input('month', now()->format('m'));
    $year = $request->input('year', now()->format('Y'));
    $departmentId = $request->input('department_id');
    $courseId = $request->input('course_id');

    // جلب قائمة الأقسام والكورسات للفلاتر
    $departments = Department::all();
    $courses = Course::when($departmentId, function ($query) use ($departmentId) {
        return $query->where('department_id', $departmentId);
    })->get();

    // الحصول على بيانات الحضور لهذا الشهر
    $attendances = Attendance::whereMonth('attendance_date', $month)
        ->whereYear('attendance_date', $year)
        ->when($courseId, function ($query) use ($courseId) {
            return $query->whereHas('session.course', function ($q) use ($courseId) {
                $q->where('id', $courseId);
            });
        })
        ->with(['student', 'session.course'])
        ->get();

    return view('admin.pages.attendance.monthly_report', compact('attendances', 'departments', 'courses', 'month', 'year', 'departmentId', 'courseId'));
}



}



    // 📌 ✅ Admin: Store Attendance Manually
//     public function store(Request $request)
//     {
//         $request->validate([
//             'student_id' => 'required|exists:students,id',
//             'session_id' => 'required|exists:course_sessions,id',
//             'status' => 'required|boolean',
//         ]);

//         Attendance::create([
//             'student_id' => $request->student_id,
//             'session_id' => $request->session_id,
//             'employee_id' => Auth::id(),
//             'attendance_date' => now(),
//             'status' => $request->status,
//         ]);

//         return redirect()->back()->with('success', 'تم تسجيل الحضور بنجاح');
//     }

//     // 📌 ✅ Admin: Update Attendance Status
//     public function update(Request $request, $id)
//     {
//         $request->validate([
//             'status' => 'required|boolean',
//         ]);

//         $attendance = Attendance::findOrFail($id);
//         $attendance->update(['status' => $request->status]);

//         return redirect()->back()->with('success', 'تم تحديث حالة الحضور بنجاح');
//     }

//     // 📌 ✅ Admin: Delete Attendance Record
//     public function destroy($id)
//     {
//         Attendance::findOrFail($id)->delete();
//         return redirect()->back()->with('success', 'تم حذف سجل الحضور بنجاح');
//     }

//    