<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Holiday;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // 🔹 عرض صفحة إدارة الحضور
    public function index(Request $request)
    {
        $departments = Department::all();

        // Fetch the request parameters
        $departmentId = $request->get('department_id');
        $courseId = $request->get('course_id');
        $sessionId = $request->get('session_id');
        $attendanceDay = $request->get('attendance_day'); // The selected day
        $studentName = $request->get('student_name');
        $status = $request->get('status');

        // Fetch holidays
        $holidays = Holiday::where('state', 1)->pluck('date')->toArray();

        // Only proceed if session_id is selected, else show empty fields and no session days
        if ($sessionId) {
            $session = CourseSession::find($sessionId);

            if ($session) {
                $startDate = Carbon::parse($session->start_date);
                $endDate = Carbon::parse($session->end_date);

                // Fetch holidays
                $holidays = Holiday::where('state', 1)->pluck('date')->toArray();

                // Generate valid session days
                $session_days = [];
                for ($date = $startDate; $date <= $endDate; $date->addDay()) {
                    // Skip Fridays and holidays
                    if ($date->dayOfWeek != Carbon::FRIDAY && !in_array($date->toDateString(), $holidays)) {
                        $session_days[] = $date->toDateString();
                    }
                }

                // Fetch the attendances for the selected session
                $attendances = Attendance::with(['student', 'session'])
                                         ->where('session_id', $sessionId)
                                         ->when($request->get('attendance_day'), function ($query) use ($request) {
                                             return $query->whereDate('attendance_date', $request->get('attendance_day'));
                                         })
                                         ->get();
            } else {
                // Handle error if session is not found
                return back()->with('error', 'الجلسة غير موجودة');
            }
        } else {
            // If no session selected, return empty session days
            $session_days = [];
            $attendances = collect();
        }

        return view('admin.pages.attendance.index', compact('attendances', 'departmentId', 'courseId', 'sessionId', 'session_days','departments'));
    }
    public function getSessionDays($sessionId)
{
    $session = CourseSession::find($sessionId);

    if (!$session) {
        return response()->json(['error' => 'الجلسة غير موجودة'], 404);
    }

    $startDate = Carbon::parse($session->start_date);
    $endDate = Carbon::parse($session->end_date);
    $holidays = Holiday::where('state', 1)->pluck('date')->toArray();

    $session_days = [];
    for ($date = $startDate; $date <= $endDate; $date->addDay()) {
        if ($date->dayOfWeek != Carbon::FRIDAY && !in_array($date->toDateString(), $holidays)) {
            $session_days[] = $date->toDateString();
        }
    }

    return response()->json($session_days);
}

    // 🔹 تغيير حالة الحضور
    public function toggleAttendance($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->status = !$attendance->status;
        $attendance->save();

        return redirect()->route('attendance.index')->with('success', 'تم تحديث حالة الحضور بنجاح.');
    }

    public function getCoursesByDepartment($departmentId)
    {
        $courses = Course::where('department_id', $departmentId)->get();
        return response()->json($courses);
    }

    public function getSessionsByCourse($courseId)
    {
        $sessions = CourseSession::where('course_id', $courseId)->get();
        return response()->json($sessions);
    }

    public function getStudentsBySession($sessionId)
    {
        // تأكد من أن الجلسة موجودة
        $session = CourseSession::find($sessionId);
        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        // استرجاع الطلاب المسجلين في الجلسة
        $students = $session->students; // العلاقة بين الجلسة والطلاب

        return response()->json($students);
    }
    public function create(Request $request)
    {
        $departmentId = $request->get('department_id');
        $courseId = $request->get('course_id');
        $sessionId = $request->get('session_id'); // Get the selected session_id

        $departments = Department::all();
        $courses = Course::where('department_id', $departmentId)->get();
        $sessions = CourseSession::where('course_id', $courseId)->get();

        // Fetch students who belong to the selected session
        $students = Student::whereHas('courseSessionStudents', function ($query) use ($sessionId) {
            $query->where('course_sessions.id', $sessionId);  // Specify the table for 'id'
        })->get();

        return view('admin.pages.attendance.create', compact('departments', 'courses', 'sessions', 'students', 'sessionId'));
    }
    public function store(Request $request)
    {
        // تحقق من تحديد الجلسة
        if (!$request->has('session_id') || !$request->session_id) {
            return redirect()->back()->with('error', 'يجب اختيار جلسة.');
        }

        $sessionId = $request->session_id;
        $session = CourseSession::find($sessionId);

        // تحقق من وجود الجلسة
        if (!$session) {
            return redirect()->back()->with('error', 'الجلسة غير موجودة.');
        }

        // تاريخ اليوم
        $today = Carbon::today();
        $startDate = Carbon::parse($session->start_date);
        $endDate = Carbon::parse($session->end_date);

        // جلب أيام الإجازات
        $holidays = Holiday::where('state', 1)->pluck('date')->toArray();

        // جلب جميع الطلاب في هذه الجلسة
        $students = Student::whereHas('courseSessionStudents', function ($query) use ($sessionId) {
            $query->where('course_sessions.id', $sessionId);
        })->get();

        // التأكد من عدم تكرار تسجيل الحضور لكل طالب بشكل منفصل
        foreach ($request->status as $studentId => $status) {
            // تحقق إذا كان الحضور مسجل لهذا الطالب في نفس الجلسة واليوم
            $attendanceExists = Attendance::where('student_id', $studentId)
                                          ->where('session_id', $sessionId)
                                          ->whereDate('attendance_date', $today)
                                          ->exists();

            // إذا كان الحضور مسجل بالفعل، إرجاع رسالة خطأ
            if ($attendanceExists) {
                return redirect()->back()->with('error', 'تم تسجيل الحضور لهذا الطالب في هذا اليوم بالفعل.');
            }

            // إذا لم يكن الحضور مسجل، قم بتسجيل الحضور
            Attendance::create([
                'student_id' => $studentId,
                'session_id' => $sessionId,
                'attendance_date' => $today,
                'status' => $status,
                'employee_id' => auth()->id(),
            ]);
        }

        return redirect()->back()->with('success', 'تم تسجيل الحضور بنجاح.');
    }

// Controller method to get students for a session
public function getStudentsForSession($sessionId)
{
    $session = CourseSession::find($sessionId);
    
    if (!$session) {
        return response()->json(['error' => 'Session not found'], 404);
    }

    // Get students enrolled in the session
    $students = $session->students;

    return response()->json($students);
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
    $departmentId = $request->input('department_id');
    $courseId = $request->input('course_id');
    $sessionId = $request->input('session_id');
    $attendanceDay = $request->input('attendance_day');
    $month = $request->input('month');
    $year = $request->input('year', now()->format('Y')); // إذا لم يتم تحديد السنة، يتم استخدام السنة الحالية

    // الحصول على الأقسام والكورسات
    $departments = Department::all();
    $courses = Course::where('department_id', $departmentId)->get();
    $sessions = CourseSession::where('course_id', $courseId)->get();

    // بناء الاستعلام بناءً على المدخلات
    $attendancesQuery = Attendance::with(['student', 'session.course']);

    // إذا تم تحديد الجلسة، نضيف فلتر الجلسة
    if ($sessionId) {
        $attendancesQuery->where('session_id', $sessionId);
    }

    if ($attendanceDay) {
        // تحويل التاريخ من المدخل إلى تنسيق متوافق مع قاعدة البيانات
        $attendanceDay = Carbon::parse($attendanceDay)->format('Y-m-d');
        $attendancesQuery->whereDate('attendance_date', $attendanceDay);
    }

    // إذا تم تحديد الشهر والسنة، نضيف فلتر الشهر والسنة
    if ($month) {
        $attendancesQuery->whereMonth('attendance_date', $month)
                         ->whereYear('attendance_date', $year);
    }

    $attendances = $attendancesQuery->get();

    // حساب نسبة الحضور
    $totalStudents = $attendances->count();
    $presentCount = $attendances->where('status', true)->count();
    $attendancePercentage = ($totalStudents > 0) ? ($presentCount / $totalStudents) * 100 : 0;

    return view('admin.pages.attendance.report', compact('attendances', 'departments', 'courses', 'sessions', 'departmentId', 'courseId', 'sessionId', 'attendancePercentage'));
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

// public function studentAttendanceHistory($studentId)
// {
//     $attendances = Attendance::where('student_id', $studentId)->get();
//     return view('admin.pages.attendance.history', compact('attendances'));
// }


// **********************teacher



public function showFormteacher()
{
    $departments = Department::all();
    $holidays = Holiday::where('state', 1)->pluck('date')->toArray();

    // تعريف المتغيرات حتى لا تظهر أخطاء
    $courses = [];
    $sessions = [];
    $students = [];
    $noStudentsMessage = null;
    $session = null;


    return view('Teacher-dashboard.presence and absence', compact(
        'departments', 'courses', 'sessions', 'students', 'noStudentsMessage', 'session',
        'holidays'
    ));
}



public function showStudentsteacher($sessionId)
{
    $session = CourseSession::findOrFail($sessionId);
    $departments = Department::where('state', 1)->get();
    $courses = Course::where('department_id', request('department_id'))->get();
    $sessions = CourseSession::where('course_id', request('course_id'))


        ->where('employee_id', auth()->user()->employee->id)
        ->get();

    $students = Student::join('course_session_students', 'students.id', '=', 'course_session_students.student_id')
        ->where('course_session_students.course_session_id', $sessionId)
        ->select('students.*')
        ->with(['degrees' => function ($query) use ($sessionId) {
            $query->where('course_session_id', $sessionId);
        }, 'courseSessions.course']) // لتحميل الكورسات أيضًا
        ->get();

    $noStudentsMessage = $students->isEmpty() ? 'لا يوجد طلاب في هذه الجلسة.' : null;

    $holidays = Holiday::pluck('date')->toArray(); // استرجاع تواريخ الإجازات من قاعدة البيانات
return view('Teacher-dashboard.presence and absence', compact(
    'departments', 'courses', 'sessions', 'session', 'students', 'noStudentsMessage', 'holidays'
));



}


public function getCoursesteacher($departmentId)
{
    $employeeId = auth()->user()->employee->id;

    // جلب الكورسات الخاصة بالقسم والنشطة التي يدرسها الاستاذ
    $courses = Course::where('department_id', $departmentId)
        ->where('state', 1) // التأكد من أن الكورسات نشطة
        ->whereHas('courseSessions', function($q) use ($employeeId) {
            $q->where('employee_id', $employeeId)->where('state', 1); // فقط الجلسات التي يشرف عليها الاستاذ والنشطة
        })
        ->get();

    return response()->json($courses);
}


public function getSessionsteacher($courseId)
{
    $employeeId = auth()->user()->employee->id;

    // جلب الجلسات الخاصة بالكورس والتي يدرسها الاستاذ
    $sessions = CourseSession::where('course_id', $courseId)
        ->where('employee_id', $employeeId) // التأكد من أن الجلسات تخص الاستاذ
        ->where('state', 1) // التأكد من أن الجلسات نشطة
        ->get();

    return response()->json($sessions);
}

public function storeteacherattendance(Request $request)
{
    // تحقق من تحديد الجلسة
    if (!$request->has('session_id') || !$request->session_id) {
        return redirect()->back()->with('error', 'يجب اختيار جلسة.');
    }

    $sessionId = $request->session_id;
    $session = CourseSession::find($sessionId);

    if (!$session) {
        return redirect()->back()->with('error', 'الجلسة غير موجودة.');
    }

    // تسجيل الحضور لجميع الطلاب
    foreach ($request->status as $studentId => $status) {
        // تأكد من عدم تكرار الحضور
        $attendanceExists = Attendance::where('student_id', $studentId)
                                      ->where('session_id', $sessionId)
                                      ->exists();

        if (!$attendanceExists) {
            Attendance::create([
                'student_id' => $studentId,
                'session_id' => $sessionId,
                'attendance_date' => now()->toDateString(),
                'status' => $status,
                'employee_id' => auth()->id(),
            ]);
        }
    }

    return redirect()->back()->with('success', 'تم تسجيل الحضور بنجاح.');
}

}
