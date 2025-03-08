<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CoursePriceController;
use App\Http\Controllers\CourseSessionController;
use App\Http\Controllers\CourseSessionStudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
// Route::get('/login', [AuthController::class, 'login'])->name('login');

// use App\Http\Controllers\Auth\RegisterController;

// Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
// Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
// Route::get('/get-courses/{department}', [RegisterController::class, 'getCourses']); // جلب الكورسات حسب القسم




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





// use App\Http\Controllers\AdminController;
// Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');


use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


use App\Http\Controllers\Admin\AdminDashboardController;


require __DIR__.'/auth.php';

Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
});
use App\Http\Controllers\TeacherDashboardController;

Route::group(['middleware' => ['role:teacher']], function () {
    Route::get('/teacher/classes', [TeacherDashboardController::class, 'index']);
});

Route::group(['middleware' => ['role:student']], function () {
    Route::get('/student/enroll', [StudentController::class, 'enroll']);
});




// use App\Http\Controllers\TeacherController;
//     Route::resource('teachers', TeacherController::class);


use App\Http\Controllers\EmployeeController;

Route::resource('employees', EmployeeController::class);

// // Resource route for courses
// Route::resource('courses', CourseController::class);

// // Custom route for filtering courses by section
// Route::get('/courses/section/{sectionId}', [CourseController::class, 'getCoursesBySection'])->name('courses.getBySection');



// Define a resource route for the DashboardController
Route::resource('/', DashboardController::class);
////////////////////////////////////////////////////////////////////////////////////
// this is for the admin dashboard

Route::resource('students', StudentController::class);

Route::get('/get-courses/{departmentId}', [CourseController::class, 'getCoursesByDepartment']);
Route::get('/get-sessions/{courseId}', [CourseSessionController::class, 'getSessionsByCourse']);


Route::get('/get-all-students', [StudentController::class, 'getAllStudents']);
Route::get('/search-students', [StudentController::class, 'searchStudents']);


// Resource route for SectionController
Route::resource('departments', DepartmentController::class);
Route::patch('/departments/{department}/toggle', [DepartmentController::class, 'toggleState'])->name('departments.toggle');


Route::resource('courses', CourseController::class);
Route::patch('/courses/{course}/toggle', [CourseController::class, 'toggleState'])->name('courses.toggle');

Route::resource('course-prices', CoursePriceController::class);
Route::patch('/course-prices/{id}/toggle', [CoursePriceController::class, 'toggle'])->name('course-prices.toggle');

//course session
Route::middleware(['auth', 'role:admin'])->group(function () {
Route::resource('course-sessions', CourseSessionController::class);
});
Route::match(['get', 'post'], '/course-sessions/{sessionId}/enroll', [CourseSessionController::class, 'enrollStudents'])->name('course-sessions.enroll');
Route::patch('/course-sessions/{id}/toggle', [CourseSessionController::class, 'toggleState'])->name('course-sessions.toggle');

Route::get('/course-sessions/{id}/enroll', [CourseSessionController::class, 'enroll'])->name('course-sessions.enroll');
Route::post('/course-sessions/{id}/store-enrollment', [CourseSessionController::class, 'storeEnrollment'])->name('course-sessions.store-enrollment');



// Resource route for StudentController
Route::get('/get-all-students', [StudentController::class, 'getAllStudents']);
Route::get('/get-courses/{departmentId}', [CourseController::class, 'getCoursesByDepartment']);
Route::get('/get-sessions/{courseId}', [CourseSessionController::class, 'getSessionsByCourse']);



// Get courses by section ID
Route::get('get-courses/{section_id}', [StudentController::class, 'getCourses'])->name('students.getCourses');

// Get students by course ID
Route::get('get-students/{course_id}', [StudentController::class, 'getStudents'])->name('students.getStudents');

// Search for students by ID or name
Route::get('search-students', [StudentController::class, 'searchStudents'])->name('students.searchStudents');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');


use App\Models\CourseSession;
use Illuminate\Http\Request;

Route::get('/teacher/sessions', function (Request $request) {
    $teacherId = auth()->id(); // Get logged-in teacher ID

    // Get the teacher's employee ID
    $employee = \App\Models\Employee::where('user_id', $teacherId)->first();
    if (!$employee) {
        return response()->json([]);
    }

    $sessions = CourseSession::where('employee_id', $employee->id)
        ->whereDate('start_date', '>=', now())
        ->get();

    // Format data for FullCalendar
    $events = [];
    foreach ($sessions as $session) {
        $events[] = [
            'title' => '📚 ' . $session->course->name,
            'start' => $session->start_date . 'T' . $session->start_time,
            'end'   => $session->end_date . 'T' . $session->end_time,
            'color' => '#007bff' // Blue color
        ];
    }

    return response()->json($events);
});

use App\Http\Controllers\HolidayController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('holidays', HolidayController::class);
});

Route::patch('/holidays/{id}/toggle', [HolidayController::class, 'toggleState'])->name('holidays.toggle');




use App\Http\Controllers\AttendanceController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/teacher/sessions', [AttendanceController::class, 'getTeacherSessions']); // عرض الجلسات التي يدرسها المعلم
    Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance']); // تسجيل الحضور
    Route::get('/attendance/session/{sessionId}', [AttendanceController::class, 'getSessionAttendance']); // عرض الحضور لجلسة
});

