<?php
namespace App\Http\Controllers;

use App\Models\CourseSession;
use App\Models\Student;
use Illuminate\Http\Request;

class CourseSessionStudentController extends Controller
{
    public function enroll($sessionId)
    {
        // جلب الجلسة والطلاب
        $session = CourseSession::findOrFail($sessionId);
        $students = Student::all(); // جميع الطلاب
        $enrolledStudents = $session->students; // الطلاب المسجلون

        return view('admin.pages.course-session-students.enroll', compact('session', 'students', 'enrolledStudents'));
    }

    public function storeEnrollment(Request $request, $sessionId)
    {
        $session = CourseSession::findOrFail($sessionId);

        $request->validate([
            'students' => 'required|array',
            'students.*' => 'exists:students,id',
        ]);

        // تسجيل الطلاب في الجلسة
        $session->students()->sync($request->students);

        return redirect()->route('course-sessions.index')->with('success', 'تم تسجيل الطلاب بنجاح!');
    }
}
