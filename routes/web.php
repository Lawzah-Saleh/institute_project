<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CoursePriceController;
use App\Http\Controllers\CourseSessionController;
use App\Http\Controllers\CourseSessionStudentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HolidayController;
use Illuminate\Http\Request;



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');

// web.php
Route::get('/students/register', [StudentController::class, 'showRegistrationForm'])->name('students.register.form');
Route::post('/students/register', [StudentController::class, 'register'])->name('students.register.submit');
Route::get('/students/invoice/{invoiceId}', [StudentController::class, 'showInvoiceConfirmation'])->name('students.invoice.view');


Route::get('/get-course-price/{course_id}', function ($course_id) {
    try {
        $price = DB::table('course_prices')
            ->where('course_id', $course_id)
            ->orderByDesc('id') // يجلب آخر سعر تمت إضافته
            ->value('price'); // نجلب السعر الصحيح

        return response()->json(['price' => $price ?? 0]); // إذا لم يوجد سعر، يرجع 0
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});


use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');




use App\Http\Controllers\ProfileController;
Route::middleware(['auth'])->group(function () {
    Route::get('/student/profile', [ProfileController::class, 'showStudentProfile'])->name('profile.student.show');
    Route::post('/profile/update', [ProfileController::class, 'updateStudentProfile'])->name('profile.student.update'); // ✅ Add this
    Route::post('/student/profile/password-update', [ProfileController::class, 'updateStudentPassword'])->name('profile.student.password.update');

});
Route::get('/courses/{id}/price', [CourseController::class, 'getPrice']);



// Route::middleware(['auth'])->group(function () {
//     // المسارات الخاصة بالطالب
//         // Routes for the student profile
//         Route::prefix('student')->middleware('role:student')->group(function () {
//             Route::get('student/profile', [ProfileController::class, 'showStudentProfile'])->name('profile.student.show');
//             Route::post('student/profile/update', [ProfileController::class, 'updateStudentProfile'])->name('profile.student.update');
//             Route::post('student/profile/update-password', [ProfileController::class, 'updateStudentPassword'])->name('profile.student.updatePassword');
//         });


//     // المسارات الخاصة بالمدرس
//     Route::prefix('teacher')->middleware('role:teacher')->group(function () {
//         Route::get('/profile', [ProfileController::class, 'showTeacherProfile'])->name('profile.teacher.show');
//         Route::post('/profile/update', [ProfileController::class, 'updateTeacherProfile'])->name('profile.teacher.update');
//         Route::post('/profile/update-password', [ProfileController::class, 'updateTeacherPassword'])->name('profile.teacher.updatePassword');
//     });

//     // المسارات الخاصة بالإداري
//     Route::prefix('admin')->middleware('role:admin')->group(function () {
//         Route::get('/profile', [ProfileController::class, 'showAdminProfile'])->name('profile.admin.show');
//         Route::post('/profile/update', [ProfileController::class, 'updateAdminProfile'])->name('profile.admin.update');
//         Route::post('/profile/update-password', [ProfileController::class, 'updateAdminPassword'])->name('profile.admin.updatePassword');
//     });
// });




require __DIR__.'/auth.php';
use App\Http\Controllers\AdminDashboardController;


Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});
use App\Http\Controllers\TeacherDashboardController;

Route::group(['middleware' => ['role:teacher']], function () {
    Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
    Route::get('/teacher/classes', [TeacherDashboardController::class, 'index'])->name('teacher.classes');
    Route::get('/teacher/sessions', [TeacherDashboardController::class, 'getTeacherSessions'])->name('teacher.sessions');

});
use App\Http\Controllers\StudentDashboardController;
Route::group(['middleware' => ['role:student']], function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
});

//employees route
Route::middleware(['auth', 'admin'])->group(function () {

    Route::resource('employees', EmployeeController::class);
    Route::post('/employees/{id}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggleStatus');
});

//student routes

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('students', StudentController::class);
    // routes/web.php
    Route::get('/students/{student}/invoice', [StudentController::class, 'showInvoice'])->name('students.invoice');
    Route::get('/students/{student}/invoice/print', [StudentController::class, 'printInvoice'])->name('students.invoice.print');
    //fix the error
    // Route::get('students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    // Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');

    Route::get('/students/search', [StudentController::class, 'showSearchStudentPage'])->name('students.search');


    Route::get('/students/register-next', [StudentController::class, 'registerNextForm'])
    ->name('students.register_next_course_form');


// تنفيذ التسجيل
Route::post('/students/register-next-course', [StudentController::class, 'registerNextCourse'])->name('students.register_next_course');

// البحث التلقائي
Route::get('/search-students', function(Request $request) {
    return \App\Models\Student::where('student_name_ar', 'LIKE', "%{$request->q}%")
            ->orWhere('student_name_en', 'LIKE', "%{$request->q}%")
            ->select('id', 'student_name_ar', 'student_name_en')
            ->limit(10)
            ->get();
});

// مسار صفحة تحديث الطالب إلى الدورة التالية
Route::get('/students/transfer', [StudentController::class, 'transferStudent'])->name('students.transfer');
Route::post('/students/transfer/{studentId}', [StudentController::class, 'processTransfer'])->name('students.processTransfer');

});
Route::get('/department/{department}/first-course', [CourseController::class, 'getFirstCourseInDepartment'])->name('department.first_course');

// courses routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('courses', CourseController::class);
    Route::patch('/courses/{course}/toggle', [CourseController::class, 'toggleState'])->name('courses.toggle');
    Route::resource('course-prices', CoursePriceController::class);
    Route::patch('/course-prices/{id}/toggle', [CoursePriceController::class, 'toggle'])->name('course-prices.toggle');
});
//course session
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('course-sessions', CourseSessionController::class);
    Route::patch('/course-sessions/{id}/toggle', [CourseSessionController::class, 'toggleState'])->name('course-sessions.toggle');

    });
//department routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('departments', DepartmentController::class);
    Route::patch('/departments/{department}/toggle', [DepartmentController::class, 'toggleState'])->name('departments.toggle');
});

Route::get('/get-courses/{departmentId}', [CourseController::class, 'getCoursesByDepartment']);
Route::get('/get-sessions/{courseId}', [CourseSessionController::class, 'getSessionsByCourse']);


// holiidays route

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('holidays', HolidayController::class);
    Route::patch('/holidays/{id}/toggle', [HolidayController::class, 'toggleState'])->name('holidays.toggle');

});



//  institute info routes

use App\Http\Controllers\InstituteController;


Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('institute', InstituteController::class);

});
// adv routes
use App\Http\Controllers\AdvertisementController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('advertisements', AdvertisementController::class);
});


use App\Http\Controllers\AttendanceController;

// ✅ إدارة الحضور عبر لوحة الإدارة (Admin Panel)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('attendance', AttendanceController::class);
    Route::patch('/attendance/{id}/toggle', [AttendanceController::class, 'toggleAttendance'])->name('attendance.toggle');
    Route::get('/admin/attendance/monthly-report', [AttendanceController::class, 'monthlyAttendanceReport'])->name('attendance.monthly_report');
    Route::get('/admin/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
    // Route::get('/get-courses/{departmentId}', [AttendanceController::class, 'getCoursesByDepartment']);
    // Route::get('/get-sessions/{courseId}', [AttendanceController::class, 'getSessionsByCourse']);
    Route::get('/get-students/{sessionId}', [AttendanceController::class, 'getStudentsBySession']);
    Route::get('/get-session-days/{sessionId}', [AttendanceController::class, 'getSessionDays']);


});
use App\Http\Controllers\DegreeController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('degrees', DegreeController::class);
    // Route::get('/get-courses/{departmentId}', [DegreeController::class, 'getCourses']);
    // Route::get('/get-sessions/{courseId}', [DegreeController::class, 'getSessions']);
    Route::get('/get-students/{sessionId}', [DegreeController::class, 'getStudents']);
    // to fix the route
    // Route::get('/admin/degrees/{sessionId}', [DegreeController::class, 'show'])->name('degrees.show');



});

use App\Http\Controllers\PaymentController;




use App\Http\Controllers\StudentAttendanceController;
Route::prefix('student')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/attendance', [StudentAttendanceController::class, 'index'])->name('student.attendance');
    Route::get('/attendance/data/{course_id}', [StudentAttendanceController::class, 'getAttendanceData']);
});

use App\Http\Controllers\StudentDegreeController;
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/degrees', [StudentDegreeController::class, 'index'])->name('student.degrees');
});



use App\Http\Controllers\StudentNotificationsController;
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/notifications', [StudentNotificationsController::class, 'index'])->name('student.notifications');
});

// payment sources
use App\Http\Controllers\PaymentSourceController;

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('payment_sources', PaymentSourceController::class);
 });



use App\Http\Controllers\StudentPaymentController;

Route::middleware(['auth', 'role:student'])->group(function () {
    // Display index page with Paid and Unpaid options
    Route::get('student/payments', [StudentPaymentController::class, 'index'])->name('student.payments');

    // Show paid invoices
    Route::get('student/payments/paid', [StudentPaymentController::class, 'showPaid'])->name('student.payment.paid');

    // Show unpaid invoices
    Route::get('student/payments/unpaid', [StudentPaymentController::class, 'showUnpaid'])->name('student.payment.unpaid');

    Route::get('student/payment/pay/{paymentId}', [StudentPaymentController::class, 'payInvoice'])->name('student.payment.pay');
    Route::post('student/payment/process/{paymentId}', [StudentPaymentController::class, 'processPayment'])->name('student.payment.process');


// لجلب السعر بناءً على الدورة المختارة
    Route::get('/get-course-price/{courseId}', [StudentPaymentController::class, 'getCoursePrice']);



});
use App\Http\Controllers\InvoiceController;
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
    Route::get('/admin/student/payments/{student}', [PaymentController::class, 'studentPaymentDetails'])
    ->name('admin.student.payment.details');
    Route::get('admin/invoices/{id}', [PaymentController::class, 'showInvoiceDetails'])->name('admin.invoices.show');

    // عرض نموذج إضافة الدفع
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('admin.payments.create');

    // حفظ عملية الدفع
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

    Route::get('/payments/invoice/{id}', [PaymentController::class, 'showInvoice'])->name('payments.invoice.show');
// راوت تحميل الفاتورة بصيغة PDF
Route::get('/payments/invoice/{id}/download', [PaymentController::class, 'downloadInvoice'])->name('admin.payments.downloadInvoice');
Route::get('admin/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
Route::put('admin/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');

Route::get('admin/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
Route::put('admin/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');



    // مسار البحث عن الطالب
    Route::get('/admin/payments/search', [PaymentController::class, 'search'])->name('admin.payments.search');
    Route::get('/admin/payments/details/{studentId}', [PaymentController::class, 'getStudentDetails']);

// مسار عرض تفاصيل الدفع
    Route::get('/admin/payments/details/{studentId}', [PaymentController::class, 'showDetails'])->name('admin.payments.details');

// إضافة الدفع للطالب
    Route::post('/admin/payments/store', [PaymentController::class, 'store'])->name('admin.payments.store');

    Route::patch('/payments/{invoice}/mark-paid', [PaymentController::class, 'markInvoicePaid'])->name('admin.payments.markPaid');
});

use App\Http\Controllers\ReportController;

Route::middleware(['auth', 'role:admin'])->group(function () {
// تقرير الطلاب المدفوعة بناءً على الفلاتر
    Route::get('admin/reports/filtered-paid-students', [ReportController::class, 'filteredPaidStudentsReport'])->name('admin.reports.filtered_paid_students');

    // بيانات الأقسام
    Route::get('admin/get-departments', [ReportController::class, 'getDepartments'])->name('admin.get_departments');

    // بيانات الكورسات بناءً على القسم
    Route::get('admin/get-courses/{departmentId}', [ReportController::class, 'getCourses'])->name('admin.get_courses');

    // بيانات الجلسات بناءً على الكورس
    Route::get('admin/get-sessions/{courseId}', [ReportController::class, 'getSessions'])->name('admin.get_sessions');
    Route::get('admin/reports/export_excel', [ReportController::class, 'exportToExcel'])->name('admin.reports.export_excel');
    Route::get('admin/reports/export_pdf', [ReportController::class, 'exportPdf'])->name('admin.reports.export_pdf');

 // بحث عن حالة الطالب المالية
Route::get('admin/reports/financial-status-search', [ReportController::class, 'financialStatusSearch'])->name('admin.reports.financial_status_search');
// عرض بيانات الطالب المالية
Route::get('admin/reports/view-student-financial-status/{studentId}', [ReportController::class, 'viewStudentFinancialStatus'])->name('admin.reports.view_student_financial_status');
Route::get('admin/reports/export_excel_financial', [ReportController::class, 'exportExcelFinancial'])->name('admin.reports.export_excel_financial');
Route::get('admin/reports/export_pdf_financial', [ReportController::class, 'exportPdfFinancial'])->name('admin.reports.export_pdf_financial');

//
Route::get('admin/reports/payment-summary', [ReportController::class, 'paymentSummaryReport'])->name('admin.reports.payment_summary');
Route::get('admin/reports/export-excel-payment-summary', [ReportController::class, 'exportExcelPaymentSummary'])->name('admin.reports.export_excel_payment_summary');
Route::get('admin/reports/export-pdf-payment-summary', [ReportController::class, 'exportPdfPaymentSummary'])->name('admin.reports.export_pdf_payment_summary');

///
Route::prefix('admin/reports')->group(function () {
    // Route for Payment Budget Report
    Route::get('/payment-budget-report', [ReportController::class, 'paymentBudgetReport'])->name('admin.reports.payment_budget_report');

    // Export to Excel
    Route::get('/export-excel-payment-budget', [ReportController::class, 'exportExcelPaymentBudget'])->name('admin.reports.export_excel_payment_budget');

    // Export to PDF
    Route::get('/export-pdf-payment-budget', [ReportController::class, 'exportPdfPaymentBudget'])->name('admin.reports.export_pdf_payment_budget');
    //

    Route::get('/payment-statement-report', [ReportController::class, 'paymentStatementReport'])->name('admin.reports.payment_statement_report');

    // Export to Excel
    Route::get('/export-excel-payment-statement', [ReportController::class, 'exportExcelPaymentStatement'])->name('admin.reports.export_excel_payment_statement');

    // Export to PDF
    Route::get('/export-pdf-payment-statement', [ReportController::class, 'exportPdfPaymentStatement'])->name('admin.reports.export_pdf_payment_statement');
    Route::get('/students-in-course-report', [ReportController::class, 'studentsInCourseReport'])->name('admin.reports.students_in_course_report');

    // Export to Excel
    Route::get('/export-excel-students-in-course', [ReportController::class, 'exportExcelStudentsInCourse'])->name('admin.reports.export_excel_students_in_course');

    // Export to PDF
    Route::get('/export-pdf-students-in-course', [ReportController::class, 'exportPdfStudentsInCourse'])->name('admin.reports.export_pdf_students_in_course');

    //
    Route::get('/students-grades-report', [ReportController::class, 'studentsGradesReport'])->name('admin.reports.students_grades_report');

    // ✅ Export to Excel
    Route::get('/admin/reports/students-grades-report/export-excel', [ReportController::class, 'exportExcelStudentsGrades'])->name('admin.reports.export_excel_students_grades');

    // ✅ Export to PDF
    Route::get('/students-grades-report/export-pdf', [ReportController::class, 'exportPdfStudentsGrades'])->name('export_pdf_students_grades');
    Route::get('/admin/reports/student-grades/{studentId}', [ReportController::class, 'viewStudentGrades'])
    ->name('admin.reports.view_student_grades');

});

Route::get('/admin/reports/student-grade', [ReportController::class, 'studentGradeSearch'])->name('admin.reports.student_grade_search');
Route::get('/admin/reports/student-grade/{studentId}', [ReportController::class, 'studentGradeDetails'])->name('admin.reports.student_grade_details');
Route::get('/admin/reports/courses_report', [ReportController::class, 'coursesReport'])->name('admin.reports.courses_report');
Route::get('/admin/reports/teachers-in-courses', [ReportController::class, 'teachersInCourses'])->name('admin.reports.teachers_in_courses');


// routes/web.php

Route::get('/admin/reports/teachers-in-courses', [ReportController::class, 'teachersInCourses'])->name('admin.reports.teachers_in_courses');
Route::get('/admin/reports/export_excel_teachers_in_courses', [ReportController::class, 'exportExcelTeachersInCourses'])->name('admin.reports.export_excel_teachers_in_courses');
Route::get('/admin/reports/export_pdf_teachers_in_courses', [ReportController::class, 'exportPdfTeachersInCourses'])->name('admin.reports.export_pdf_teachers_in_courses');
Route::get('admin/reports/courses-on-date', [ReportController::class, 'coursesOnDate'])->name('admin.reports.courses_on_date');

Route::get('admin/reports/courses-status', [ReportController::class, 'coursesStatus'])->name('admin.reports.courses_status');

// Route to display the attendance report with filters
Route::get('/admin/reports/attendance-report', [ReportController::class, 'attendanceReport'])
    ->name('admin.reports.attendance_report');

// Route to export the attendance report to Excel
Route::get('/admin/reports/export-excel-attendance', [ReportController::class, 'exportExcelAttendance'])
    ->name('admin.reports.export_excel_attendance');

// Route to export the attendance report to PDF
Route::get('/admin/reports/export-pdf-attendance', [ReportController::class, 'exportPdfAttendance'])
    ->name('admin.reports.export_pdf_attendance');




});
use App\Http\Controllers\CertificateController;
use App\Models\Attendance;

// صفحة البحث عن الطالب
Route::get('search-student', [CertificateController::class, 'searchStudentForm'])->name('student.search');
Route::post('search-student', [CertificateController::class, 'searchStudent'])->name('student.search.submit');

// لإصدار الشهادة
Route::get('certificate/{studentId}/{courseSessionId}', [CertificateController::class, 'generateCertificate'])
    ->name('certificate.generate');

// In web.php (routes)
Route::get('/get-students/{sessionId}', [AttendanceController::class, 'getStudentsForSession']);






///////////////////////////////
// teachers

Route::get('/teacher/get-courses/{departmentId}', [StudentController::class, 'getCoursesteacher'])->middleware(['auth', 'is_teacher'])->name('teacher.get.courses');

Route::get('/T-students/{sessionId}', [StudentController::class, 'showStudentsteacher'])
->middleware(['auth', 'is_teacher'])  // إضافة ميديوير للمصادقة
->name('degrees.show');


// Route::get('/get-courses/{departmentId}', [StudentController::class, 'getCoursesteacher'])
// ->middleware(['auth', 'is_teacher'])  // إضافة ميديوير للمصادقة
// ->name('get.courses');

Route::get('teacher/get-sessions/{courseId}', [StudentController::class, 'getSessionsteacher'])
->middleware(['auth', 'is_teacher'])  // إضافة ميديوير للمصادقة
->name('get.sessions');




Route::get('/search-students', [StudentController::class, 'searchStudentst']);


Route::get('/T-courses', [CourseSessionController::class, 'coursrteacher'])
    ->middleware(['auth', 'is_teacher']);

    Route::middleware(['auth', 'role:teacher'])->group(function () {
        Route::get('/T-students', [StudentController::class, 'showFormteacher'])->name('student.degrees.form');
    });


// profile
Route::middleware(['auth'])->group(function () {
    Route::get('/T-profile', [ProfileController::class, 'indexteacher'])->name('teacher.profile');
});



// الباسورد
Route::middleware(['auth'])->group(function () {
    Route::put('/teacher/update-password', [ProfileController::class, 'updatePassword'])->name('teacher.update-password');
});


Route::post('/teacher/change-password', [ProfileController::class, 'changePassword'])
    ->middleware(['auth'])
    ->name('teacher.change-password');


Route::get('/edit-profile-T', [ProfileController::class, 'showEditProfile'])
    ->middleware(['auth', 'is_teacher']) // إضافة ميدل وير is_teacher
    ->name('Teacher-dashboard.edit-profile-T');

    Route::get('/edit-profile-T', [ProfileController::class, 'editProfile'])
    ->middleware(['auth', 'is_teacher'])
    ->name('teacher.edit-profile');


Route::post('/update-profile', [ProfileController::class, 'updateProfileteacher'])
    ->middleware(['auth', 'is_teacher']) // إضافة ميدل وير is_teacher
    ->name('teacher.update-profile');

//end profile

            // إضافة route لصفحة عرض النتيجة بعد حفظ الدرجات
Route::get('/add-result', [DegreeController::class, 'showFormteacher'])
->middleware(['auth', 'is_teacher'])
->name('Teacher-dashboard.add-result');


Route::get('/add-result/{sessionId}', [DegreeController::class, 'showStudentsteacher'])
    ->middleware(['auth', 'is_teacher'])  // إضافة ميديوير للمصادقة
    ->name('degrees.show');


// Route::get('/get-courses/{departmentId}', [DegreeController::class, 'getCoursesteacher'])
//     ->middleware(['auth', 'is_teacher'])  // إضافة ميديوير للمصادقة
//     ->name('get.courses');

// Route::get('/get-sessions/{courseId}', [DegreeController::class, 'getSessionsteacher'])
//     ->middleware(['auth', 'is_teacher'])  // إضافة ميديوير للمصادقة
//     ->name('get.sessions');

// Route::get('/presence and absence', [AttendanceController::class, 'showFormteacher'])
//     ->middleware(['auth', 'is_teacher'])  // إضافة ميديوير للمصادقة
//     ->name('degrees.form');
//attendance
    // Route::get('/teacher/attendance', [AttendanceController::class, 'showStudentsteacher'])    ->middleware(['auth', 'is_teacher'])  // إضافة ميديوير للمصادقة
    // ->name('teacherattendance.show');
// Route for showing the attendance page
// Route::get('/teacher/attendance/{sessionId}', [AttendanceController::class, 'showStudentsteacher'])->name('teacher.attendance');


// Route لعرض صفحة الحضور
Route::get('/teacher/attendance', [AttendanceController::class, 'showAttendanceForm'])->name('teacher.attendance.form');

// Route لحفظ الحضور
Route::post('/teacher/attendance/store', [AttendanceController::class, 'storeAttendance'])->name('teacher.attendance.store');
// Route::post('/attendance/store', [AttendanceController::class, 'storeteacherattendance'])
//     ->middleware(['auth', 'is_teacher'])  // إضافة ميديوير للمصادقة
//     ->name('degrees.store');

Route::post('/degrees/store', [DegreeController::class, 'storeteacher'])
    ->middleware(['auth', 'is_teacher'])  // إضافة ميديوير للمصادقة
    ->name('degrees.store');

// Route::get('/get-courses/{departmentId}', [AttendanceController::class, 'getCoursesteacher'])
//     ->middleware(['auth', 'is_teacher'])  // إضافة ميديوير للمصادقة
//     ->name('get.courses');

// Route::get('/get-sessions/{courseId}', [AttendanceController::class, 'getSessionsteacher'])
//     ->middleware(['auth', 'is_teacher'])  // إضافة ميديوير للمصادقة
//     ->name('get.sessions');
