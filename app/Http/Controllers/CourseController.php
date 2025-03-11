<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\CourseSession;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $departmentId = $request->department_id;

        // إذا كان هناك فلتر حسب القسم
        if ($departmentId) {
            $courses = Course::where('department_id', $departmentId)->get();
        } else {
            $courses = Course::all();
        }

        $departments = Department::all();

        return view('admin.pages.courses.index', compact('courses', 'departments'));
    }


    public function toggleState(Course $course)
    {
        $course->state = !$course->state;
        $course->save();

        return redirect()->route('courses.index')->with('success', 'تم تحديث حالة الكورس بنجاح!');
    }
            public function create()
        {
            $departments = Department::all(); // جلب الأقسام لإضافتها في القائمة المنسدلة
            return view('admin.pages.courses.create', compact('departments'));
        }

        public function store(Request $request)
        {
            $request->validate([
                'course_name' => 'required|string|max:255',
                'duration' => 'nullable|integer',
                'description' => 'nullable|max:200',
                'department_id' => 'required|exists:departments,id',
                'state' => 'required|boolean',
            ]);

            Course::create([
                'course_name' => $request->course_name,
                'duration' => $request->duration,
                'description' => $request->description,
                'department_id' => $request->department_id,
                'state' => $request->state,
            ]);

            return redirect()->route('courses.index')->with('success', 'تم إضافة الدورة بنجاح!');
        }
        public function edit($id)
        {
            // جلب الكورس المحدد
            $course = Course::findOrFail($id);

            // جلب جميع الأقسام
            $departments = Department::all();

            // عرض صفحة التعديل
            return view('admin.pages.courses.edit', compact('course', 'departments'));
        }


        public function update(Request $request, $id)
        {
            // تحقق من صحة البيانات
            $request->validate([
                'course_name' => 'required|string|max:255',
                'duration' => 'nullable|integer',
                'description' => 'nullable|max:200',
                'department_id' => 'required|exists:departments,id',
                'state' => 'required|boolean',
            ]);

            // جلب الكورس وتحديث البيانات
            $course = Course::findOrFail($id);
            $course->update($request->all());

            // إعادة التوجيه مع رسالة نجاح
            return redirect()->route('courses.index')->with('success', 'تم تعديل الكورس بنجاح!');
        }public function getCoursesByDepartment($departmentId)
        {
            $courses = Course::where('department_id', $departmentId)->get();
            return response()->json($courses);
        }
        public function getSessions($courseId)
{
    $sessions = CourseSession::where('course_id', $courseId)->get();
    return response()->json($sessions);
}








}
