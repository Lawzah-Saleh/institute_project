<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\CourseSession;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function searchStudentForm()
    {
        return view('admin.pages.certificates.search_student');
    }

    // البحث عن الطالب
    public function searchStudent(Request $request)
    {
        $students = Student::query()
            ->where('student_name_ar', 'like', '%' . $request->search . '%')
            ->orWhere('student_name_en', 'like', '%' . $request->search . '%')
            ->orWhere('email', 'like', '%' . $request->search . '%')
            ->get();

        return view('admin.pages.certificates.search_student', compact('students'));
    }

    // إصدار الشهادة
    public function generateCertificate($studentId, $courseSessionId)
    {
        $student = Student::findOrFail($studentId);
        $courseSession = CourseSession::findOrFail($courseSessionId);
        return view('admin.pages.certificates.certificate', compact('student', 'courseSession'));
    }
}
