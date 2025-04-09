<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Session;
use App\Models\Department;
use App\Models\Payment;
use App\Models\CourseSession;
use App\Models\Degree;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaidStudentsExport;
use App\Exports\FinancialStatusExport;
use App\Exports\PaymentSummaryExport;
use App\Exports\StudentsGradesExport;

use Carbon\Carbon;

class ReportController extends Controller
{
    // تقرير الطلاب المدفوعة بناءً على الفلاتر
    public function filteredPaidStudentsReport(Request $request)
    {
        // استرجاع الفلاتر من الطلب
        $departmentId = $request->input('department_id');
        $courseId     = $request->input('course_id');
        $sessionId    = $request->input('session_id');
    
        // استعلام الطلاب مع العلاقات
        $studentsQuery = Student::query()
            ->with(['invoices', 'payments']) // لجلب الفواتير والمدفوعات
            ->withSum('invoices', 'amount')  // جمع المبلغ المستحق من الفواتير
            ->withSum('payments', 'total_amount'); // جمع المبلغ المدفوع من المدفوعات
    
        // تصفية بناءً على القسم إذا كان موجودًا
        if ($departmentId) {
            $studentsQuery->whereHas('courses', function ($query) use ($departmentId) {
                $query->where('courses.department_id', $departmentId); // تحديد الجدول المحدد
            });
        }
    
        // تصفية بناءً على الدورة إذا كانت موجودة
        if ($courseId) {
            $studentsQuery->whereHas('courses', function ($query) use ($courseId) {
                $query->where('courses.id', $courseId); // تحديد الجدول المحدد
            });
        }
    
        if ($sessionId) {
            $studentsQuery->whereHas('sessions', function ($q) use ($sessionId) {
                $q->where('course_sessions.id', $sessionId);
            });
        }

        // جلب الطلاب
        $students = $studentsQuery->get();
    
        // جلب جميع الأقسام
        $departments = Department::all();
    
        // جلب الدورات إذا كان القسم محددًا
        $courses = $departmentId ? Course::where('department_id', $departmentId)->get() : [];
    
        // إرجاع البيانات إلى العرض
        return view('admin.pages.reports.filtered_paid_students', [
            'students' => $students,
            'departments' => $departments,
            'courses' => $courses,
        ]);
    }
    public function financialStatusSearch(Request $request)
    {
        // استرجاع البحث من الطلب
        $search = $request->input('search');
    
        // استعلام الطلاب بناءً على البحث (يمكنك البحث عن الاسم أو البريد الإلكتروني أو رقم الطالب)
        $studentsQuery = Student::query();
    
        if ($search) {
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('student_name_ar', 'like', "%$search%")
                  ->orWhere('student_name_en', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('id', 'like', "%$search%");
            });
        }
    
        // جلب الطلاب الذين تطابقوا مع البحث
        $students = $studentsQuery->with(['invoices', 'payments'])
            ->withSum('invoices', 'amount')  // جمع المبلغ المستحق من الفواتير
            ->withSum('payments', 'total_amount') // جمع المبلغ المدفوع من المدفوعات
            ->get();
    
        return view('admin.pages.reports.financial_status_search', [
            'students' => $students,
            'search' => $search
        ]);
    }
    public function viewStudentFinancialStatus($studentId)
{
    // جلب بيانات الطالب
    $student = Student::with(['invoices', 'payments'])
        ->withSum('invoices', 'amount')
        ->withSum('payments', 'total_amount')
        ->findOrFail($studentId);

    // حساب المبلغ المتبقي
    $totalPaid = $student->payments_sum_total_amount;
    $totalAmount = $student->invoices_sum_amount;
    $remainingAmount = $totalAmount - $totalPaid;

    return view('admin.pages.reports.view_student_financial_status', compact('student', 'totalPaid', 'totalAmount', 'remainingAmount'));
}
public function exportExcelFinancial(Request $request)
{
    // الحصول على قيمة البحث
    $search = $request->input('search');

    // تصفية البيانات بناءً على البحث
    $studentsQuery = Student::query();

    if ($search) {
        $studentsQuery->where(function ($q) use ($search) {
            $q->where('student_name_ar', 'like', "%$search%")
              ->orWhere('student_name_en', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('id', 'like', "%$search%");
        });
    }

    $students = $studentsQuery->with(['invoices', 'payments'])
        ->withSum('invoices', 'amount')
        ->withSum('payments', 'total_amount')
        ->get();

    // تصدير البيانات إلى Excel
    return Excel::download(new FinancialStatusExport($students), 'financial_status_report.xlsx');
}
public function exportPdfFinancial(Request $request)
{
    // الحصول على قيمة البحث
    $search = $request->input('search');

    // تصفية البيانات بناءً على البحث
    $studentsQuery = Student::query();

    if ($search) {
        $studentsQuery->where(function ($q) use ($search) {
            $q->where('student_name_ar', 'like', "%$search%")
              ->orWhere('student_name_en', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('id', 'like', "%$search%");
        });
    }

    $students = $studentsQuery->with(['invoices', 'payments'])
        ->withSum('invoices', 'amount')
        ->withSum('payments', 'total_amount')
        ->get();

    // تصدير البيانات إلى PDF
    $pdf = PDF::loadView('admin.pages.reports.financial_status_pdf', [
        'students' => $students,
    ]);

    return $pdf->download('financial_status_report.pdf');
}

public function paymentSummaryReport(Request $request)
{
    // استرجاع الفلاتر من الطلب
    $period        = $request->input('period', 'today');  // افتراض اليوم كالفترة الافتراضية
    $departmentId  = $request->input('department_id');

    // بناء الاستعلام
    $paymentsQuery = Payment::query()->with('student');

    // تصفية حسب القسم إذا كان موجودًا
    if ($departmentId) {
        $paymentsQuery->whereHas('student.courses', function ($query) use ($departmentId) {
            $query->where('courses.department_id', $departmentId);
        });
    }

    // تصفية بناءً على الفترة الزمنية
    $now = \Carbon\Carbon::now();
    switch ($period) {
        case 'month':
            $paymentsQuery->whereMonth('created_at', $now->month);
            break;
        case 'year':
            $paymentsQuery->whereYear('created_at', $now->year);
            break;
        case 'today':
        default:
            $paymentsQuery->whereDate('created_at', $now->toDateString());
            break;
    }

    // جلب المدفوعات
    $payments = $paymentsQuery->get();

    // جلب جميع الأقسام
    $departments = Department::all();

    return view('admin.pages.reports.payment_summary', [
        'payments'    => $payments,
        'departments' => $departments,
    ]);
}
public function paymentBudgetReport(Request $request)
{
    $period = $request->input('period', 'today'); // افتراض اليوم كالفترة الافتراضية
    $departmentId = $request->input('department_id');
    
    $now = \Carbon\Carbon::now();

    // بناء الاستعلام لعرض المدفوعات والفواتير
    $paymentsQuery = Payment::query()->with('student');

    // تصفية حسب القسم
    if ($departmentId) {
        $paymentsQuery->whereHas('student.courses', function ($query) use ($departmentId) {
            $query->where('courses.department_id', $departmentId);
        });
    }

    // تصفية حسب الفترة الزمنية
    switch ($period) {
        case 'month':
            $paymentsQuery->whereMonth('created_at', $now->month);
            break;
        case 'year':
            $paymentsQuery->whereYear('created_at', $now->year);
            break;
        case 'today':
        default:
            $paymentsQuery->whereDate('created_at', $now->toDateString());
            break;
    }

    // جلب المدفوعات
    $payments = $paymentsQuery->get();

    // حساب إجمالي المبالغ المستحقة والمدفوعة
    $totalPaid = $payments->sum('total_amount');
    $totalDue = $payments->sum(function ($payment) {
        return $payment->student->invoices->sum('amount');
    });

    $remainingAmount = $totalDue - $totalPaid;

    // جلب الأقسام
    $departments = Department::all();

    return view('admin.pages.reports.payment_budget_report', compact('payments', 'totalPaid', 'totalDue', 'remainingAmount', 'period', 'departments'));
}
public function paymentStatementReport(Request $request)
{
    // الحصول على تاريخ البداية والنهاية من الطلب
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    
    // تحويل التواريخ إلى الكائنات DateTime
    $startDate = Carbon::parse($startDate);
    $endDate = Carbon::parse($endDate)->endOfDay(); // لضمان أخذ كامل اليوم

    // بناء الاستعلام لعرض المدفوعات
    $paymentsQuery = Payment::query()->with('student');

    // تصفية المدفوعات بين تاريخين
    $paymentsQuery->whereBetween('created_at', [$startDate, $endDate]);

    // جلب المدفوعات
    $payments = $paymentsQuery->get();

    return view('admin.pages.reports.payment_statement_report', compact('payments', 'startDate', 'endDate'));
}

// تصدير المدفوعات إلى Excel
public function exportExcelPaymentStatement(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    
    $startDate = Carbon::parse($startDate);
    $endDate = Carbon::parse($endDate)->endOfDay(); 

    // بناء الاستعلام لعرض المدفوعات
    $paymentsQuery = Payment::query()->with('student');
    $paymentsQuery->whereBetween('created_at', [$startDate, $endDate]);

    $payments = $paymentsQuery->get();

    return Excel::download(new PaymentStatementExport($payments), 'payment_statement.xlsx');
}

// تصدير المدفوعات إلى PDF
public function exportPdfPaymentStatement(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    
    $startDate = Carbon::parse($startDate);
    $endDate = Carbon::parse($endDate)->endOfDay(); 

    // بناء الاستعلام لعرض المدفوعات
    $paymentsQuery = Payment::query()->with('student');
    $paymentsQuery->whereBetween('created_at', [$startDate, $endDate]);

    $payments = $paymentsQuery->get();

    $pdf = PDF::loadView('admin.pages.reports.payment_statement_pdf', compact('payments', 'startDate', 'endDate'));

    return $pdf->download('payment_statement.pdf');
}
public function exportExcelPaymentSummary(Request $request)
{
    $period = $request->input('period', 'today');
    $departmentId = $request->input('department_id');

    $paymentsQuery = Payment::query()->with('student');

    if ($departmentId) {
        $paymentsQuery->whereHas('student.courses', function ($query) use ($departmentId) {
            $query->where('courses.department_id', $departmentId);
        });
    }

    $now = \Carbon\Carbon::now();
    switch ($period) {
        case 'month':
            $paymentsQuery->whereMonth('created_at', $now->month);
            break;
        case 'year':
            $paymentsQuery->whereYear('created_at', $now->year);
            break;
        case 'today':
        default:
            $paymentsQuery->whereDate('created_at', $now->toDateString());
            break;
    }

    $payments = $paymentsQuery->get();

    return Excel::download(new PaymentSummaryExport($payments), 'payment_summary.xlsx');
}
public function exportPdfPaymentSummary(Request $request)
{
    $period = $request->input('period', 'today');
    $departmentId = $request->input('department_id');

    $paymentsQuery = Payment::query()->with('student');

    if ($departmentId) {
        $paymentsQuery->whereHas('student.courses', function ($query) use ($departmentId) {
            $query->where('courses.department_id', $departmentId);
        });
    }

    $now = \Carbon\Carbon::now();
    switch ($period) {
        case 'month':
            $paymentsQuery->whereMonth('created_at', $now->month);
            break;
        case 'year':
            $paymentsQuery->whereYear('created_at', $now->year);
            break;
        case 'today':
        default:
            $paymentsQuery->whereDate('created_at', $now->toDateString());
            break;
    }

    $payments = $paymentsQuery->get();

    $pdf = PDF::loadView('admin.pages.reports.payment_summary_pdf', ['payments' => $payments]);

    return $pdf->download('payment_summary.pdf');
}

public function exportToExcel(Request $request)
{
    $departmentId = $request->input('department_id');
    $courseId = $request->input('course_id');
    $sessionId = $request->input('session_id');

    // تصدير البيانات باستخدام الفلاتر التي تم تحديدها
    return Excel::download(new PaidStudentsExport($departmentId, $courseId, $sessionId), 'paid_students.xlsx');
}
public function exportPdf(Request $request)
{
    // If you need to get the filtered students from the request
    $departmentId = $request->input('department_id');
    $courseId = $request->input('course_id');
    $sessionId = $request->input('session_id');
    
    // Generate the report data (can be similar to your Excel export logic)
    $students = Student::with(['payments'])
                       ->when($departmentId, function ($query) use ($departmentId) {
                           $query->where('department_id', $departmentId);
                       })
                       ->when($courseId, function ($query) use ($courseId) {
                           $query->where('course_id', $courseId);
                       })
                       ->when($sessionId, function ($query) use ($sessionId) {
                           $query->whereHas('sessions', function ($q) use ($sessionId) {
                               $q->where('session_id', $sessionId);
                           });
                       })
                       ->get();

    // Prepare the PDF with the collected data
    $pdf = PDF::loadView('admin.pages.reports.paid_students_pdf', compact('students'));

    // Return the generated PDF for download
    return $pdf->download('paid_students_report.pdf');
}

    // بيانات الأقسام والكورسات والجلسات
    public function getDepartments()
    {
        $departments = Department::all();
        return response()->json($departments);
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
       // دالة لإظهار تقرير كشف بيانات الطلاب بالدورة
       public function studentsInCourseReport(Request $request)
       {
           // الحصول على الفلاتر من الطلب
           $departmentId = $request->input('department_id');
           $courseId = $request->input('course_id');
           $sessionId = $request->input('session_id');
           $search = $request->input('search');
   
           // جلب الأقسام
           $departments = Department::all();
   
           // جلب الدورات بناءً على القسم المحدد
           $courses = $departmentId ? Course::where('department_id', $departmentId)->get() : [];
   
           // جلب الجلسات بناءً على الدورة المحددة
           $sessions = $courseId ? CourseSession::where('course_id', $courseId)->get() : [];
   
           // بناء الاستعلام للطلاب
           $studentsQuery = Student::query()->with('courses', 'sessions');
   
           // تصفية بناءً على البحث عن طالب
           if ($search) {
               $studentsQuery->where(function ($query) use ($search) {
                   $query->where('student_name_ar', 'like', "%$search%")
                         ->orWhere('student_name_en', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%");
               });
           }
   
           // تصفية بناءً على القسم المحدد
           if ($departmentId) {
               $studentsQuery->whereHas('courses', function ($query) use ($departmentId) {
                   $query->where('department_id', $departmentId);
               });
           }
   
        // تصفية بناءً على الدورة إذا كانت موجودة
        if ($courseId) {
            $studentsQuery->whereHas('courses', function ($query) use ($courseId) {
                $query->where('courses.id', $courseId); // تحديد الجدول المحدد
            });
        }
    
        if ($sessionId) {
            $studentsQuery->whereHas('sessions', function ($q) use ($sessionId) {
                $q->where('course_sessions.id', $sessionId);
            });
        }
   
           // جلب الطلاب
           $students = $studentsQuery->get();
   
           // إرجاع البيانات إلى العرض
           return view('admin.pages.reports.students_in_course_report', compact('students', 'departments', 'courses', 'sessions', 'departmentId', 'courseId', 'sessionId', 'search'));
       }

       public function studentsGradesReport(Request $request)
{
    $departmentId = $request->input('department_id');
    $courseId     = $request->input('course_id');
    $sessionId    = $request->input('session_id');
    $search       = $request->input('search');

    // 🔎 استعلام الطلاب حسب الدورة والجلسة
    $studentsQuery = Student::query();

    // علاقات الجلسة والدورة
    $studentsQuery->whereHas('sessions', function ($q) use ($sessionId) {
        if ($sessionId) {
            $q->where('course_sessions.id', $sessionId);
        }
    });

    if ($departmentId) {
        $studentsQuery->whereHas('courses', function ($q) use ($departmentId) {
            $q->where('courses.department_id', $departmentId);
        });
    }

    if ($courseId) {
        $studentsQuery->whereHas('courses', function ($q) use ($courseId) {
            $q->where('courses.id', $courseId);
        });
    }

    if ($search) {
        $studentsQuery->where(function ($q) use ($search) {
            $q->where('student_name_ar', 'like', "%$search%")
              ->orWhere('student_name_en', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('phones', 'like', "%$search%");
        });
    }

    // ⚠️ تأكد أن العلاقة sessions تستخدم pivot يحتوي على: attendance_score, final_score
    $students = $studentsQuery->with(['sessions' => function ($q) use ($sessionId) {
        if ($sessionId) {
            $q->where('course_sessions.id', $sessionId);
        }
    }])->get();

    // جلب الأقسام والدورات والجلسات
    $departments = Department::all();
    $courses = $departmentId ? Course::where('department_id', $departmentId)->get() : [];
    $sessions = $courseId ? CourseSession::where('course_id', $courseId)->get() : [];

    // جلب معلومات الجلسة الحالية
    $sessionInfo = $sessionId ? CourseSession::with('course', 'employee')->find($sessionId) : null;

    return view('admin.pages.reports.students_grades_report', [
        'students'    => $students,
        'departments' => $departments,
        'courses'     => $courses,
        'sessions'    => $sessions,
        'sessionInfo' => $sessionInfo,
    ]);
}
public function exportExcelStudentsGrades(Request $request)
{
    // Retrieving the filters from the request
    $departmentId = $request->input('department_id');
    $courseId     = $request->input('course_id');
    $sessionId    = $request->input('session_id');
    $search       = $request->input('search');

    // Get students based on the filters
    $studentsQuery = Student::query();

    if ($departmentId) {
        $studentsQuery->whereHas('courses', function ($q) use ($departmentId) {
            $q->where('courses.department_id', $departmentId);
        });
    }

    if ($courseId) {
        $studentsQuery->whereHas('courses', function ($q) use ($courseId) {
            $q->where('courses.id', $courseId);
        });
    }

    if ($sessionId) {
        $studentsQuery->whereHas('sessions', function ($q) use ($sessionId) {
            $q->where('course_sessions.id', $sessionId);
        });
    }

    if ($search) {
        $studentsQuery->where(function ($q) use ($search) {
            $q->where('student_name_ar', 'like', "%$search%")
              ->orWhere('student_name_en', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('phones', 'like', "%$search%");
        });
    }

    // Get the students with the sessions
    $students = $studentsQuery->with('sessions')->get();

    // Export to Excel
    return Excel::download(new StudentsGradesExport($students), 'students_grades_report.xlsx');
}
public function viewStudentGrades(Request $request, $studentId)
{
    // Fetch the student details, grades, and related data
    $student = Student::with(['courses', 'sessions', 'grades'])
        ->findOrFail($studentId); // Retrieve student by ID, if exists
    
    // Fetch the student's course session details
    $courses = $student->courses; // Assuming a student can have multiple courses
    $sessions = $student->sessions; // Assuming a student can have multiple sessions
    
    // Calculate or retrieve the total grade and attendance grade (assuming they are stored or calculated)
    $totalGrade = $student->grades()->sum('grade'); // Sum of all grades for the student
    $attendanceGrade = $student->attendance_grade; // Assuming attendance grade is stored

    // Calculate the final grade if necessary
    $finalGrade = $this->calculateFinalGrade($totalGrade, $attendanceGrade);

    // Return to the view with student, grades, and related data
    return view('admin.pages.reports.student_grades_details', [
        'student' => $student,
        'courses' => $courses,
        'sessions' => $sessions,
        'totalGrade' => $totalGrade,
        'attendanceGrade' => $attendanceGrade,
        'finalGrade' => $finalGrade
    ]);
}

private function calculateFinalGrade($totalGrade, $attendanceGrade)
{
    // Simple logic for calculating the final grade (you can adjust this as per your requirements)
    return $totalGrade + $attendanceGrade;
}
public function studentGradeSearch(Request $request)
{
    $students = Student::where('student_name_ar', 'like', '%' . $request->search . '%')
        ->orWhere('student_name_en', 'like', '%' . $request->search . '%')
        ->get();

    return view('admin.pages.reports.student_grade_search', compact('students'));
}

// لعرض الدرجات التفصيلية للطالب في دورة معينة
public function studentGradeDetails($studentId)
{
    $student = Student::with(['courses', 'degrees'])->findOrFail($studentId);

    // جمع الدرجات لكل دورة
    $degrees = Degree::where('student_id', $studentId)
        ->with(['session.course']) // ربط الجلسات والدورات
        ->get();

    return view('admin.pages.reports.student_grade_details', compact('student', 'degrees'));
}

    // دالة لعرض تقرير الدورات المتاحة
    public function coursesReport(Request $request)
    {
        $departments = Department::all();  // جلب جميع الأقسام
        $coursesQuery = Course::query();

        // تصفية حسب القسم
        if ($request->has('department_id')) {
            $coursesQuery->where('department_id', $request->department_id);
        }

        // جلب الدورات المصفاة
        $courses = $coursesQuery->get();

        return view('admin.pages.reports.courses_report', compact('courses', 'departments'));
    }
// app/Http/Controllers/ReportController.php

public function teachersInCourses(Request $request)
{
    $departmentId = $request->input('department_id');
    $timePeriod = $request->input('time_period'); // فترة الوقت

    // جلب الدورات المرتبطة بالقسم المحدد
    $coursesQuery = Course::query();

    // تصفية حسب القسم إذا تم تحديده
    if ($departmentId) {
        $coursesQuery->where('department_id', $departmentId);
    }

    // جلب الدورات مع الجلسات والمدرسين (المدرس مرتبط بكل جلسة)
    $courses = $coursesQuery->with(['sessions' => function($query) use ($timePeriod) {
        if ($timePeriod) {
            // تصفية الجلسات بناءً على الفترة الزمنية
            switch ($timePeriod) {
                case '8-10':
                    $query->whereBetween('start_time', ['08:00:00', '10:00:00']);
                    break;
                case '10-12':
                    $query->whereBetween('start_time', ['10:00:00', '12:00:00']);
                    break;
                case '2-4':
                    $query->whereBetween('start_time', ['14:00:00', '16:00:00']);
                    break;
                case '4-6':
                    $query->whereBetween('start_time', ['16:00:00', '18:00:00']);
                    break;
            }
        }
    }])->get();

    // جلب الأقسام لعرضها في الفلتر
    $departments = Department::all();
    
    return view('admin.pages.reports.teachers_in_courses', compact('courses', 'departments'));
}
public function coursesOnDate(Request $request)
{
    $selectedDate = $request->input('selected_date'); // الحصول على التاريخ المختار

    // جلب الدورات التي تقام في التاريخ المحدد
    $coursesQuery = Course::query();

    // تصفية الجلسات بناءً على التاريخ المحدد
    if ($selectedDate) {
        $coursesQuery->whereHas('sessions', function($query) use ($selectedDate) {
            $query->whereDate('start_date', '=', $selectedDate);
        });
    }

    // جلب الدورات مع الجلسات والمدرسين
    $courses = $coursesQuery->with(['sessions' => function($query) use ($selectedDate) {
        $query->whereDate('start_date', '=', $selectedDate);
    }, 'sessions.employee'])->get();

    return view('admin.pages.reports.courses_on_date', compact('courses', 'selectedDate'));
}
}
