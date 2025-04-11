<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Degree;
use App\Models\Student;
use App\Models\Course;
use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\Department;


class DegreeController extends Controller
{
    // عرض جميع الدرجات
    public function index()

    {
        $departments = Department::all();
        $degrees = Degree::with('student', 'session')->get();
        return view('admin.pages.degrees.index', compact('degrees', 'departments'));
    }

    // عرض نموذج إضافة درجة
    public function create()
    {
        $departments = Department::all();
        return view('admin.pages.degrees.create', compact('departments'));
    }
    public function show($sessionId)
    {
        // جلب الجلسة المحددة
        $session = CourseSession::findOrFail($sessionId);

        // جلب الطلاب المسجلين في الجلسة
        $students = Student::join('course_session_students', 'students.id', '=', 'course_session_students.student_id')
            ->where('course_session_students.course_session_id', $sessionId)
            ->select('students.*')
            ->with(['degrees' => function ($query) use ($sessionId) {
                $query->where('course_session_id', $sessionId);
            }])->get();

        // جلب جميع الأقسام
        $departments = Department::all();

        return view('admin.pages.degrees.create', compact('departments', 'session', 'students'));
    }




    public function getCourses($departmentId)
    {
        $courses = Course::where('department_id', $departmentId)->get();
        return response()->json($courses);
    }

    public function getSessions($courseId)
    {
        $sessions = CourseSession::where('course_id', $courseId)->get();
        return response()->json($sessions);
    }

    public function getStudents($sessionId)
    {
        // التحقق مما إذا كانت الجلسة موجودة
        if (!CourseSession::find($sessionId)) {
            return response()->json(['error' => 'الجلسة غير موجودة!'], 404);
        }

        // جلب الطلاب المسجلين في هذه الجلسة من جدول `course_session_students`
        $students = Student::whereHas('courseSessionStudents', function ($query) use ($sessionId) {
            $query->where('course_session_students.course_session_id', $sessionId);
        })->with(['degrees' => function ($query) use ($sessionId) {
            $query->where('course_session_id', $sessionId);
        }])->get();

        // التحقق مما إذا كان هناك طلاب
        if ($students->isEmpty()) {
            return response()->json(['message' => 'لا يوجد طلاب مسجلين في هذه الجلسة.'], 200);
        }

        // حساب درجة الحضور لكل طالب
        foreach ($students as $student) {
            $totalSessions = Attendance::where('session_id', $sessionId)->count();
            $attendedSessions = Attendance::where('student_id', $student->id)
                ->where('session_id', $sessionId)
                ->where('status', 'present')
                ->count();

            $student->attendance_degree = ($totalSessions > 0) ? ($attendedSessions / $totalSessions) * 100 : 0;
            $student->total_degree = ($student->degree->practical_degree ?? 0) +
                                     ($student->degree->final_degree ?? 0) +
                                     $student->attendance_degree;
        }

        return response()->json($students);
    }

    public function store(Request $request)
    {
        foreach ($request->practical_degree as $studentId => $practicalDegree) {
            $finalDegree = $request->final_degree[$studentId];

            $totalSessions = Attendance::where('session_id', $request->session_id)->count();
            $attendedSessions = Attendance::where('student_id', $studentId)
                ->where('session_id', $request->session_id)
                ->where('status', 'present')
                ->count();
                $attendanceDegree = $this->calculateAttendance($studentId, $request->session_id);

                $totalDegree = $this->calculateTotalDegree($practicalDegree, $finalDegree, $attendanceDegree);


        $state = '0';

        Degree::updateOrCreate(
            [
                'student_id' => $studentId,
                'course_session_id' => $request->session_id
            ],
            [
                'practical_degree' => $practicalDegree,
                'final_degree' => $finalDegree,
                'attendance_degree' => $attendanceDegree,  // تم استخدام المتغير هنا
                'total_degree' => $totalDegree,  // تم إضافة المتغير هنا
                'state' => $state,
            ]
        );
    }


        return redirect()->back()->with('success', 'تم حفظ الدرجات بنجاح!');
    }
// حساب درجة الحضور بناءً على عدد الحضور
// حساب درجة الحضور بناءً على عدد الجلسات التي حضرها الطالب
public function calculateAttendance($studentId, $sessionId)
{
    // عدد الجلسات التي تم عقدها في الجلسة المحددة
    $totalSessions = Attendance::where('session_id', $sessionId)->count();

    // عدد الجلسات التي حضرها الطالب
    $attendedSessions = Attendance::where('student_id', $studentId)
                                   ->where('session_id', $sessionId)
                                   ->where('status', 'present') // هنا نحسب الجلسات التي كان الطالب حاضر فيها
                                   ->count();

    // حساب درجة الحضور
    return ($totalSessions > 0) ? ($attendedSessions / $totalSessions) * 10 : 0;
}

    public function calculateTotalDegree($practicalDegree, $finalDegree, $attendanceDegree)
    {
        return $practicalDegree + $finalDegree + $attendanceDegree;
    }


    // عرض نموذج تعديل الدرجة
    public function edit($id)
    {
        $degree = Degree::findOrFail($id);
        $students = Student::all();
        $sessions = CourseSession::all();
        return view('admin.pages.degrees.edit', compact('degree', 'students', 'sessions'));
    }

    // تعديل الدرجة في قاعدة البيانات
    public function update(Request $request, $id)
    {
        $request->validate([
            'practical_degree' => 'required|numeric|min:0|max:100',
            'final_degree' => 'required|numeric|min:0|max:100',
            'attendance_degree' => 'required|numeric|min:0|max:100',
        ]);

        $degree = Degree::findOrFail($id);

        $total = $request->practical_degree + $request->final_degree + $request->attendance_degree;
        $status = ($total >= 50) ? 'pass' : 'fail';

        $degree->update([
            'practical_degree' => $request->practical_degree,
            'final_degree' => $request->final_degree,
            'attendance_degree' => $request->attendance_degree,
            'total_degree' => $total,
            'status' => $status
        ]);

        return redirect()->route('degrees.index')->with('success', 'تم تعديل الدرجة بنجاح!');
    }

    // حذف الدرجة
    public function destroy($id)
    {
        Degree::findOrFail($id)->delete();
        return redirect()->route('degrees.index')->with('success', 'تم حذف الدرجة بنجاح!');
    }



// ****************teacher

public function showFormteacher()
{
    $departments = Department::where('state', 1)->get();

    // تعريف المتغيرات حتى لا تظهر أخطاء
    $courses = [];
    $sessions = [];
    $students = [];
    $noStudentsMessage = null;
    $session = null;

    return view('Teacher-dashboard.add-result', compact(
        'departments', 'courses', 'sessions', 'students', 'noStudentsMessage', 'session'
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

    return view('Teacher-dashboard.add-result', compact(
        'departments', 'courses', 'sessions', 'session', 'students', 'noStudentsMessage'
    ));
}









public function storeteacher(Request $request)
{
    $sessionId = $request->session_id;

    // تحقق إذا كانت الدرجات موجودة مسبقًا
    if (!$request->has('practical_degree') || empty($request->practical_degree)) {
        return redirect()->back()->with('error', 'لقد تم إضافة الدرجات مسبقًا.');
    }

    foreach ($request->practical_degree as $studentId => $practical) {
        $final = $request->final_degree[$studentId];

        // التحقق من أن الدرجات ضمن الحدود المسموحة
        if ($practical > 50 || $final > 40) {
            return redirect()->back()->with('error', 'الدرجة العملية لا يمكن أن تتجاوز 50، والدرجة النهائية لا يمكن أن تتجاوز 40.');
        }

        // تحقق إذا كان الطالب قد أضاف الدرجات بالفعل أم لا
        $existingDegree = Degree::where('student_id', $studentId)
            ->where('course_session_id', $sessionId)
            ->first();

        if ($existingDegree) {
            continue;
        }

        // حساب درجة الحضور
        $attendanceDegree = $this->calculateAttendanceteacher($studentId, $sessionId);

        // إذا لم يتم إضافة درجة عملية أو نهائية، نضعها إلى 0
        $practicalDegree = $practical ?? 0;
        $finalDegree = $final ?? 0;

        Degree::create([
            'student_id' => $studentId,
            'course_session_id' => $sessionId,
            'practical_degree' => $practicalDegree,
            'final_degree' => $finalDegree,
            'attendance_degree' => $attendanceDegree,
            'total_degree' => $practicalDegree + $finalDegree + $attendanceDegree,
        ]);
    }

    // إعادة تحميل الصفحة مع عرض الدرجات المدخلة
    return redirect()->route('Teacher-dashboard.add-result', ['sessionId' => $sessionId])
        ->with('success', 'تم حفظ الدرجات بنجاح.');
}




private function calculateAttendanceteacher($studentId, $sessionId)
{
    $total = Attendance::where('session_id', $sessionId)->count();
    $attended = Attendance::where('student_id', $studentId)->where('session_id', $sessionId)->where('status', 'present')->count();
    return ($total > 0) ? ($attended / $total) * 10 : 0;
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


}
