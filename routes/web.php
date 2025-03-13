<?php

use App\Http\Controllers\ProfileController;
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


Route::get('/registeration', [StudentController::class, 'showRegistrationForm'])->name('students.register');
Route::post('/registeration', [StudentController::class, 'register']);

Route::get('/get-courses/{department}', function ($department) {
    try {
        $courses = DB::table('courses')
            ->where('department_id', $department)
            ->select('id', 'course_name') // تعديل الاسم الصحيح
            ->get();

        return response()->json($courses);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
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


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




require __DIR__.'/auth.php';
use App\Http\Controllers\AdminDashboardController;


Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});
use App\Http\Controllers\TeacherDashboardController;

Route::group(['middleware' => ['role:teacher']], function () {
    Route::get('/teacher/classes', [TeacherDashboardController::class, 'index']);
    Route::get('/teacher/sessions', [TeacherDashboardController::class, 'getTeacherSessions'])->name('teacher.sessions');

});

Route::group(['middleware' => ['role:student']], function () {
    Route::get('/student/enroll', [StudentController::class, 'enroll']);
});

//employees route
Route::middleware(['auth', 'admin'])->group(function () {

    Route::resource('employees', EmployeeController::class);
    Route::post('/employees/{id}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggleStatus');
});

//student routes

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('students', StudentController::class);

});

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

// // addendance routes
// use App\Http\Controllers\AttendanceController;

// Route::middleware(['auth', 'role:teacher'])->group(function () {
//     Route::get('/teacher/sessions', [AttendanceController::class, 'getTeacherSessions'])->name('teacher.sessions');
//     Route::get('/attendance/session/{sessionId}', [AttendanceController::class, 'getSessionAttendance'])->name('attendance.session');
//     Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
// });

// use App\Http\Controllers\AdminAttendanceController;

// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/admin/attendance', [AdminAttendanceController::class, 'index'])->name('admin.attendance.index');
//     Route::patch('/admin/attendance/{id}/update', [AdminAttendanceController::class, 'update'])->name('admin.attendance.update');
// });

use App\Http\Controllers\AttendanceController;

// ✅ إدارة الحضور عبر لوحة الإدارة (Admin Panel)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('attendance', AttendanceController::class);
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('admin.attendance.index');
    Route::patch('/attendance/{id}/toggle', [AttendanceController::class, 'toggleAttendance'])->name('attendance.toggle');

    // 🔹 جلب الكورسات بناءً على القسم المختار
    Route::get('/get-courses/{departmentId}', [AttendanceController::class, 'getCoursesByDepartment']);
    
    // 🔹 جلب الجلسات بناءً على الكورس المختار
    Route::get('/get-sessions/{courseId}', [AttendanceController::class, 'getSessionsByCourse']);
        // Route::get('/admin/attendance/report', [AttendanceController::class, 'report'])->name('admin.attendance.report');
        Route::get('/admin/attendance/monthly-report', [AttendanceController::class, 'monthlyAttendanceReport'])->name('attendance.monthly_report');
        Route::get('/admin/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');

        Route::get('/get-courses/{departmentId}', [CourseController::class, 'getCoursesByDepartment']);
        
});



