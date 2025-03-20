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
    public function create(Request $request)
    {
        $departments = Department::all();
        $courses = collect();
        $sessions = collect();
        $students = collect();
        $sessionId = null; // Initialize $sessionId to null

        // If course_id is selected, load courses
        if ($request->has('course_id')) {
            $courseId = $request->course_id;
            $courses = Course::where('department_id', $request->department_id)->get();
        }

        // If session_id is selected, load sessions and students
        if ($request->has('session_id')) {
            $sessionId = $request->session_id;  // Set sessionId to the selected session
            $sessions = CourseSession::where('course_id', $request->course_id)->get();

            // Get the students for the selected session
            $students = Student::whereHas('courseSessionStudents', function ($query) use ($sessionId) {
                $query->where('course_sessions.id', $sessionId);
            })->get();
        }

        return view('admin.pages.degrees.create', compact('departments', 'courses', 'sessions', 'students', 'sessionId'));
    }
    public function store(Request $request)
    {
        // التحقق من البيانات
 // Validate request data
$request->validate([
    'final_degree.*' => 'nullable|numeric|min:0|max:100',
    'practical_degree.*' => 'nullable|numeric|min:0|max:100',
    'attendance_degree.*' => 'nullable|numeric|min:0|max:10',
]);

// Ensure session_id is not null
if (!$request->has('session_id') || !$request->session_id) {
    return redirect()->back()->with('error', 'يجب اختيار الجلسة.');
}

// Ensure student_id is in an array format before processing
if ($request->has('student_id') && is_array($request->student_id)) {
    foreach ($request->student_id as $index => $studentId) {
        // Check if the index exists in the arrays
        $finalDegree = isset($request->final_degree[$index]) ? $request->final_degree[$index] : 0; // Default to 0 if not set
        $practicalDegree = isset($request->practical_degree[$index]) ? $request->practical_degree[$index] : 0;
        $attendanceDegree = isset($request->attendance_degree[$index]) ? $request->attendance_degree[$index] : 0;

        // Calculate total degree
        $totalDegree = $finalDegree + $practicalDegree + $attendanceDegree;

        // Update or create the degree record for each student
        Degree::updateOrCreate(
            [
                'student_id' => $studentId,
                'course_session_id' => $request->session_id
            ],
            [
                'final_degree' => $finalDegree,
                'practical_degree' => $practicalDegree,
                'attendance_degree' => $attendanceDegree,
                'total_degree' => $totalDegree, // Store the total degree
            ]
        );
    }

    return redirect()->route('degrees.index')->with('success', 'تم تسجيل الدرجات بنجاح.');
}

return redirect()->back()->with('error', 'لم يتم العثور على الطلاب.');

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
        $students = Student::whereHas('courseSessionStudents', function ($query) use ($sessionId) {
            $query->where('course_sessions.id', $sessionId);
        })->get();

        // حساب درجة الحضور من جدول الحضور
        $students->map(function ($student) use ($sessionId) {
            $attendanceDegree = Attendance::where('student_id', $student->id)
                ->where('session_id', $sessionId)
                ->where('status', true)
                ->count() * 2; // مثال: كل حضور يعادل درجتين

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
