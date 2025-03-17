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

    public function store(Request $request)
    {
        foreach ($request->student_id as $studentId) {
            Degree::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'session_id' => $request->session_id,
                ],
                [
                    'practical_degree' => $request->practical_degree[$studentId],
                    'final_degree' => $request->final_degree[$studentId],
                    'attendance_degree' => $request->attendance_degree[$studentId],
                    'total_degree' => $request->practical_degree[$studentId] + $request->final_degree[$studentId] + $request->attendance_degree[$studentId],
                    'status' => ($request->practical_degree[$studentId] + $request->final_degree[$studentId] + $request->attendance_degree[$studentId]) >= 50 ? 'ناجح' : 'راسب',
                ]
            );
        }

        return redirect()->back()->with('success', 'تم حفظ الدرجات بنجاح');
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
        $students = Student::whereHas('sessions', function ($query) use ($sessionId) {
            $query->where('course_session_id', $sessionId);
        })->get();

        $students->map(function ($student) use ($sessionId) {
            $attendanceDegree = Attendance::where('student_id', $student->id)
                ->where('session_id', $sessionId)
                ->where('status', true)
                ->count() * 2; // Example: Each attendance is worth 2 marks

            $student->attendance_degree = $attendanceDegree;
            return $student;
        });

        return view('admin.pages.degrees.partials.students', compact('students'))->render();
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
