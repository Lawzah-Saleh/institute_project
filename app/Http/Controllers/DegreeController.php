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
        })->with(['degree' => function ($query) use ($sessionId) {
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


        $status = $totalDegree >= 50 ? 'pass' : 'fail';

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
                'status' => $status,
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
}
