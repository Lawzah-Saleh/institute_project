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
    Route::get('students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');

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
    Route::get('/get-courses/{departmentId}', [AttendanceController::class, 'getCoursesByDepartment']);
    Route::get('/get-sessions/{courseId}', [AttendanceController::class, 'getSessionsByCourse']);
    Route::get('/get-students/{sessionId}', [AttendanceController::class, 'getStudentsBySession']);
    Route::get('/get-session-days/{sessionId}', [AttendanceController::class, 'getSessionDays']);


});
use App\Http\Controllers\DegreeController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('degrees', DegreeController::class);
    Route::get('/get-courses/{departmentId}', [DegreeController::class, 'getCourses']);
    Route::get('/get-sessions/{courseId}', [DegreeController::class, 'getSessions']);
    Route::get('/get-students/{sessionId}', [DegreeController::class, 'getStudents']);
    Route::get('/admin/degrees/{sessionId}', [DegreeController::class, 'show'])->name('degrees.show');



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

    // Show the payment page for a specific invoice
    Route::get('student/payments/pay/{invoice_id}', [StudentPaymentController::class, 'showPaymentPage'])->name('student.payment.pay');

    // Store payment data
    Route::get('/student/payment/receipt', [StudentPaymentController::class, 'storePayment'])->name('student.payment.receipt');

    Route::post('/payment/receipt/{invoice_id}', [StudentPaymentController::class, 'payInvoice'])->name('student.payment.pay.invoice');
    // Mark invoice as paid
    Route::post('student/payments/pay/{invoice_id}/mark-as-paid', [StudentPaymentController::class, 'markAsPaid'])->name('student.payment.markAsPaid');


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


 
    // مسار البحث عن الطالب
    Route::get('/admin/payments/search', [PaymentController::class, 'search'])->name('admin.payments.search');
    Route::get('/admin/payments/details/{studentId}', [PaymentController::class, 'getStudentDetails']);

// مسار عرض تفاصيل الدفع
    Route::get('/admin/payments/details/{studentId}', [PaymentController::class, 'showDetails'])->name('admin.payments.details');

// إضافة الدفع للطالب
    Route::post('/admin/payments/store', [PaymentController::class, 'store'])->name('admin.payments.store');

    Route::patch('/payments/{invoice}/mark-paid', [PaymentController::class, 'markInvoicePaid'])->name('admin.payments.markPaid');
});

// // routes/api.php
// use App\Http\Controllers\Api\StudentSearchController;

// Route::get('/students/search', [StudentSearchController::class, 'search']);
