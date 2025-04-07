<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePrice;
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

    public function getCoursesByDepartment($departmentId)
    {
        $courses = Course::where('department_id', $departmentId)->get();
        return response()->json($courses);
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
            // التحقق من المدخلات
            $request->validate([
                'course_name' => 'required|string|max:255',
                'duration' => 'nullable|integer',
                'description' => 'nullable|max:200',
                'department_id' => 'required|exists:departments,id',
                'state' => 'required|boolean',
                'price' => 'required|numeric|min:0', // التحقق من السعر
            ]);

            // إضافة الدورة إلى جدول الدورات
            $course = Course::create([
                'course_name' => $request->course_name,
                'duration' => $request->duration,
                'description' => $request->description,
                'department_id' => $request->department_id,
                'state' => $request->state,
            ]);

            // إضافة السعر إلى جدول course_prices باستخدام الـ id الخاص بالكورس
            CoursePrice::create([
                'course_id' => $course->id, // ربط السعر بالكورس
                'price' => $request->price, // تخزين السعر
                'date'=>now(),
                'price_approval'=>now(),
                'state'=>'1',

            ]);

            // إعادة التوجيه مع رسالة نجاح
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
        }
        public function getSessions($courseId)
{
    $sessions = CourseSession::where('course_id', $courseId)->get();
    return response()->json($sessions);
}
public function getFirstCourseInDepartment($departmentId)
{
    $course = Course::where('department_id', $departmentId)->orderBy('id')->first();

    if ($course) {
        return response()->json([$course]);
    }

    return response()->json([]);
}


}
