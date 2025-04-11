<?php
namespace App\Http\Controllers;

use App\Models\CourseSession;
use App\Models\Course;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class CourseSessionController extends Controller
{
    public function index()
    {
        $sessions = CourseSession::with('course', 'employee')->get();
        return view('admin.pages.course_sessions.index', compact('sessions'));
    }

    public function create()
    {
        $courses = Course::all();
        $employees = Employee::where('emptype', 'teacher')->get();
        return view('admin.pages.course_sessions.create', compact('courses', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'employee_id' => 'nullable|exists:employees,id',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'daily_hours' => 'required|integer|min:2|max:4',
        ]);

        // Ensure `daily_hours` is available before calculating `end_date`
        $endDate = null;
        if ($request->daily_hours) {
            $endDate = CourseSession::calculateEndDate($request->course_id, $request->start_date, $request->daily_hours);
        }

        CourseSession::create([
            'course_id' => $request->course_id,
            'employee_id' => $request->employee_id ?? null,
            'start_date' => $request->start_date,
            'end_date' => $endDate,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'daily_hours' => $request->daily_hours,
            'state' => $request->state ?? 1,
        ]);

        return redirect()->route('course-sessions.index')->with('success', 'تمت إضافة الجلسة الدراسية بنجاح.');
    }

    public function edit($id)
    {
        $session = CourseSession::findOrFail($id);
        $courses = Course::all();
        $employees = Employee::where('emptype', 'teacher')->get();

        return view('admin.pages.course_sessions.edit', compact('session', 'courses', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'employee_id' => 'nullable|exists:employees,id',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'daily_hours' => 'required|integer|min:2|max:4',
        ]);

        $session = CourseSession::findOrFail($id);

        // Ensure `daily_hours` is available before calculating `end_date`
        $endDate = null;
        if ($request->daily_hours) {
            $endDate = CourseSession::calculateEndDate($request->course_id, $request->start_date, $request->daily_hours);
        }

        $session->update([
            'course_id' => $request->course_id,
            'employee_id' => $request->employee_id ?? null,
            'start_date' => $request->start_date,
            'end_date' => $endDate,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'daily_hours' => $request->daily_hours,
            'state' => $request->state ?? $session->state,
        ]);

        return redirect()->route('course-sessions.index')->with('success', 'تم تعديل الجلسة بنجاح.');
    }
    public function getSessionsByCourse($courseId)
    {
        // Fetch only sessions that belong to the selected course
        $sessions = CourseSession::where('course_id', $courseId)->get();

        return response()->json($sessions);
    }



    public function destroy(CourseSession $courseSession)
    {
        $courseSession->delete();
        return redirect()->route('course-sessions.index')->with('success', 'تم حذف الجلسة الدراسية.');
    }


        // ******************teacher





        public function coursrteacher()
        {
    
    
    
    
            // ✅ جلب بيانات الموظف المرتبط بالمستخدم الحالي
            $employee = Auth::user()->employee;
    
            // ✅ التحقق من أن المستخدم فعلاً معلم
            if (!$employee || $employee->emptype !== 'teacher') {
                return redirect()->route('login')->with('error', 'ليس لديك الصلاحية للوصول إلى هذه الصفحة.');
            }
    
            // ✅ جلب الكورسات المرتبطة بالمعلم فقط عندما تكون نشطة (status = 1)
            $courseSessions = CourseSession::where('employee_id', $employee->id)
                ->where('state', 1)              // ✅ فقط الكورسات النشطة
                ->with('course')                 // ✅ تحميل علاقة الكورس المرتبط
                ->get();
    
            // ✅ إرسال البيانات إلى صفحة العرض
            return view('Teacher-dashboard.T-courses', compact('courseSessions'));
        }
    
    
    
    
}
