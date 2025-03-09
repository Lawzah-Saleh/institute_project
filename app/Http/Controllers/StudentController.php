<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Student;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\CourseSessionStudent;
use App\Models\CourseStudent;
use App\Models\Department;
use App\Models\User;
use App\Models\Invoice;


use Illuminate\Http\Request;


class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        // Fetch departments, courses, and sessions for filters
        $departments = Department::all();
        $courses = []; // لا يتم عرض أي كورسات في البداية
        if ($request->filled('department_id')) {
            $courses = Course::where('department_id', $request->department_id)->get();
        }
        $sessions = CourseSession::all();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', $search)
                  ->orWhere('student_name_ar', 'LIKE', "%{$search}%")
                  ->orWhere('student_name_en', 'LIKE', "%{$search}%");
        }
        // Apply filtering based on selected department
        if ($request->filled('department_id')) {
            $query->whereHas('courses', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // Apply filtering based on selected course
        if ($request->filled('course_id')) {
            $query->whereHas('courses', function ($q) use ($request) {
                $q->where('courses.id', $request->course_id);
            });
        }

        // Apply filtering based on selected session
        if ($request->filled('session_id')) {
            $query->whereHas('sessions', function ($q) use ($request) {
                $q->where('course_sessions.id', $request->session_id);
            });
        }

        $students = $query->get();

        return view('admin.pages.students.index', compact('students', 'departments', 'courses', 'sessions'));
    }

    public function getAllStudents()
    {
        try {
            $students = Student::with(['department', 'courses', 'sessions'])->get();
            return response()->json($students);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء تحميل بيانات الطلاب.'], 500);
        }
    }




    public function searchStudents(Request $request)
    {
        $query = Student::with(['course.department', 'sessions']);

        if ($request->has('id') && $request->id) {
            $query->where('id', $request->id);
        }

        if ($request->has('name') && $request->name) {
            $query->where('student_name_ar', 'like', '%' . $request->name . '%');
        }

        if ($request->has('department_id') && $request->department_id) {
            $query->whereHas('course.department', function ($q) use ($request) {
                $q->where('id', $request->department_id);
            });
        }

        $students = $query->get();
        return response()->json($students);
    }


    public function create()
    {
        $departments = Department::all(); // جلب جميع الأقسام
        return view('admin.pages.students.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_name_ar' => 'required|max:300',
            'student_name_en' => 'required|max:300',
            'phone' => 'required|max:15',
            'gender' => 'required|in:male,female',
            'qualification' => 'nullable|max:150',
            'birth_date' => 'required|date',
            'birth_place' => 'required|max:150',
            'address' => 'required|max:300',
            'email' => 'nullable|email|unique:students,email',
            'course_id' => 'nullable|exists:courses,id',
            'session_id' => 'nullable|exists:course_sessions,id',
            'state' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['state'] = $request->state === 'active' ? 1 : 0;

        // إنشاء المستخدم
        $email = $validated['email'] ?? $validated['student_name_en'] . '@default.com';
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $validated['student_name_en'],
                'email' => $email,
                'password' => bcrypt('123456'),
            ]);
        }

        // ✅ **إضافة دور الطالب للمستخدم إذا لم يكن موجودًا**
        if (!$user->hasRole('student')) {
            $user->assignRole('student');
        }

        // إنشاء الطالب وربطه بالمستخدم
        $validated['user_id'] = $user->id;
        $student = Student::create($validated);

        // ✅ **حفظ الصورة إذا تم رفعها**
        if ($request->hasFile('image')) {
            $student->image = $request->file('image')->store('students');
            $student->save();
        }

        $courseId = $request->course_id;

        // ✅ **إذا تم اختيار جلسة فقط، احصل على `course_id` من الجلسة المحددة**
        if ($request->filled('session_id')) {
            $session = CourseSession::find($request->session_id);
            if ($session) {
                $courseId = $session->course_id; // استخراج `course_id` من الجلسة
            }

            // ✅ **إضافة الطالب إلى `course_session_students`**
            CourseSessionStudent::create([
                'student_id' => $student->id,
                'session_id' => $request->session_id,
                'status' => 'active',
            ]);
        }

        // ✅ **إضافة الطالب إلى `course_students` إذا لم يكن مسجلًا بالفعل**
        if ($courseId) {
            CourseStudent::updateOrCreate(
                ['student_id' => $student->id, 'course_id' => $courseId],
                [
                    'register_at' => now(),
                    'study_time' => $request->input('study_time', '08-10'),
                ]
            );
        }

        return redirect()->route('students.index')->with('success', 'تم تسجيل الطالب بنجاح.');
    }



    public function edit($id)
    {
        $student = Student::with('courses', 'sessions')->findOrFail($id);
        $departments = Department::all();
        $courses = Course::where('department_id', $student->department_id)->get();
        $sessions = CourseSession::where('course_id', $student->course_id)->get();

        return view('admin.pages.students.edit', compact('student', 'departments', 'courses', 'sessions'));
    }


    public function update(Request $request, $id)
    {
        // 🔹 التحقق من صحة البيانات
        $validated = $request->validate([
            'student_name_ar' => 'required|max:300',
            'student_name_en' => 'required|max:300',
            'phone' => 'required|max:15',
            'gender' => 'required|in:male,female',
            'qualification' => 'nullable|max:150',
            'birth_date' => 'required|date',
            'birth_place' => 'required|max:150',
            'address' => 'required|max:300',
            'email' => 'nullable|email|unique:students,email,'.$id,
            'department_id' => 'required|exists:departments,id',
            'course_id' => 'nullable|exists:courses,id',
            'session_id' => 'nullable|exists:course_sessions,id',
            'state' => 'required|in:active,inactive,suspended,expelled,graduated',
        ]);

        // 🔹 جلب الطالب وتحديث البيانات
        $student = Student::findOrFail($id);
        $student->update($validated);

        // 🔹 تحديث الكورسات والجلسات
        if ($request->filled('session_id')) {
            // حذف أي تسجيل قديم للطالب في الجلسات
            CourseSessionStudent::where('student_id', $student->id)->delete();

            // تسجيل الطالب في الجلسة الجديدة
            CourseSessionStudent::create([
                'student_id' => $student->id,
                'session_id' => $request->session_id,
                'status' => 'active',
            ]);
        } elseif ($request->filled('course_id')) {
            // حذف أي تسجيل قديم للطالب في الكورسات
            CourseStudent::where('student_id', $student->id)->delete();

            // تسجيل الطالب في الكورس الجديد
            CourseStudent::create([
                'student_id' => $student->id,
                'course_id' => $request->course_id,
                'register_at' => now(),
                'study_time' => '08-10', // الوقت الافتراضي
            ]);
        }

        return redirect()->route('students.index')->with('success', 'تم تعديل بيانات الطالب بنجاح.');
    }



    //حق صفحة التسجيل
        public function showRegistrationForm()
        {
            $departments = Department::with('courses')->get();
            return view('admin.pages.students.register', compact('departments'));
        }

        public function register(Request $request)
        {
            return DB::transaction(function () use ($request) {
                Log::info('Starting student registration.', ['request_data' => $request->all()]);

                // ✅ التحقق من صحة البيانات
                $request->validate([
                    'student_name_ar' => 'required|string|max:255',
                    'student_name_en' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email|unique:students,email',
                    'phone' => 'required|string',
                    'address' => 'required|string',
                    'gender' => 'required|in:Male,Female',
                    'qualification' => 'required|string',
                    'birth_date' => 'required|date',
                    'birth_place' => 'required|string',
                    'image' => 'nullable|image|max:2048',
                    'department' => 'required|exists:departments,id',
                    'course_id' => 'required|exists:courses,id',
                    'time' => 'required|in:8-10,10-12,2-4,4-6',
                    'amount_paid' => 'required|numeric|min:0'
                ]);

                Log::info('Validation passed.');

                // ✅ رفع الصورة إن وجدت
                $imagePath = $request->hasFile('image') ? $request->file('image')->store('students/images', 'public') : null;

                Log::info('Image uploaded successfully.', ['image_path' => $imagePath]);

                // ✅ إنشاء مستخدم للطالب
                $user = User::create([
                    'name' => $request->student_name_en,
                    'email' => $request->email,
                    'password' => Hash::make('123456'),
                ]);

                Log::info('User created.', ['user_id' => $user->id]);

                // ✅ إنشاء الطالب وربطه بالمستخدم
                $student = Student::create([
                    'user_id' => $user->id,
                    'student_name_ar' => $request->student_name_ar,
                    'student_name_en' => $request->student_name_en,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'gender' => $request->gender,
                    'qualification' => $request->qualification,
                    'birth_date' => $request->birth_date,
                    'birth_place' => $request->birth_place,
                    'image' => $imagePath,
                    'state' => '0',
                ]);

                Log::info('Student created.', ['student_id' => $student->id]);

                // ✅ التأكد من أن المدفوعات لا تتجاوز سعر الكورس
                $coursePrice = DB::table('course_prices')
                    ->where('course_id', $request->course_id)
                    ->orderByDesc('id')
                    ->value('price') ?? 0;

                if (!$coursePrice) {
                    Log::error('No price found for course.', ['course_id' => $request->course_id]);
                    throw new \Exception('No price found for the selected course.');
                }

                if ($request->amount_paid > $coursePrice) {
                    return back()->withErrors([
                        'amount_paid' => '⚠️ المبلغ المدفوع لا يمكن أن يكون أكبر من سعر الكورس: ' . $coursePrice . ' ريال'
                    ])->withInput();
                }


                // ✅ إنشاء الحافظة (`Invoice`)
                $invoice = Invoice::create([
                    'student_id' => $student->id,
                    'amount' => $request->amount_paid, // المبلغ المدفوع من الطالب
                    'status' => '0',
                    'invoice_number' => 'INV-' . time(),
                    'invoice_details' => "Fees for course: " . Course::find($request->course_id)->course_name,
                    'due_date' => now()->addDays(7),
                    'paid_at' => null,
                ]);

                Log::info('Invoice created.', ['invoice_id' => $invoice->id]);

                // ✅ تسجيل الطالب في `course_students` مع الوقت الذي اختاره
                CourseStudent::create([
                    'student_id' => $student->id,
                    'course_id' => $request->course_id,
                    'time' => $request->time,
                    'status' => 'pending',
                ]);

                Log::info('Course registration successful.');

                return redirect()->route('students.index')->with('success', 'Student, invoice, and course registration created successfully!');
            }, 5);
        }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'تم حذف الطالب بنجاح.');
    }
    public function teacherStudents()
    {
        $students = Student::all();
        return view('Teacher-dashboard.students', compact('students'));
    }
}
