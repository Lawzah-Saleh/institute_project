<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Student;
use App\Models\Course;
use App\Models\CoursePrice;
use App\Models\CourseSession;
use App\Models\CourseSessionStudent;
use App\Models\CourseStudent;
use App\Models\Department;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentSource;
use PDF; // إذا تستخدم barryvdh/laravel-dompdf


use Illuminate\Http\Request;


class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        $departments = Department::all();
        $courses = Course::all();
        $sessions = CourseSession::all();

        // 🔍 البحث عن الطلاب بالاسم أو الرقم
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', $searchTerm)
                  ->orWhere('student_name_ar', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('student_name_en', 'LIKE', "%{$searchTerm}%");
            });
        }

        // 🔍 فلترة الطلاب بناءً على القسم
        if ($request->filled('department_id')) {
            $query->whereHas('courses', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            })->orWhereHas('sessions.course', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // 🔍 فلترة الطلاب بناءً على الكورس
        if ($request->filled('course_id')) {
            $query->whereHas('courses', function ($q) use ($request) {
                $q->where('courses.id', $request->course_id);
            })->orWhereHas('sessions', function ($q) use ($request) {
                $q->whereHas('course', function ($q) use ($request) {
                    $q->where('id', $request->course_id);
                });
            });
        }

        // 🔍 فلترة الطلاب بناءً على الجلسة
        if ($request->filled('course_session_id')) {
            $query->whereHas('sessions', function ($q) use ($request) {
                $q->where('course_sessions.id', $request->course_session_id);
            });
        }

        // تحميل العلاقات لضمان ظهور البيانات
        $students = $query->with(['courses', 'sessions.course'])->get();

        return view('admin.pages.students.index', compact('students', 'departments', 'courses', 'sessions'));
    }
    public function show($id)
    {
        // 🟢 Fetch the student along with related courses and sessions
        $student = Student::with(['courses', 'sessions'])->findOrFail($id);

        return view('admin.pages.students.show', compact('student'));
    }

  
  public function create()
    {
        $departments = Department::all(); // جلب جميع الأقسام
        $courses = Course::all(); // جلب جميع الكورسات
        return view('admin.pages.students.create', compact('departments', 'courses'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_name_ar' => 'required|max:300',
            'student_name_en' => 'required|max:300',
            'phones' => 'required|array',
            'phones.*' => 'string|max:20',
            'gender' => 'required|in:male,female',
            'qualification' => 'required|max:255',
            'birth_date' => 'required|date',
            'birth_place' => 'required|max:150',
            'address' => 'required|max:300',
            'email' => 'nullable|email|unique:students,email',
            'state' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
    
            // الكورس والجلسة
            'course_id' => 'required|exists:courses,id',
            'study_time' => 'nullable|in:8-10,10-12,12-2,2-4,4-6',
            'course_session_id' => 'nullable|exists:course_sessions,id',
    
            // الدفع
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,mail',
        ]);
    
        DB::beginTransaction();
    
        try {
            // ✅ إنشاء المستخدم
            $email = $validated['email'] ?? $validated['student_name_en'] . '@default.com';
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $validated['student_name_en'], 'password' => bcrypt('123456')]
            );
            if (!$user->hasRole('student')) $user->assignRole('student');
    
            // ✅ إنشاء الطالب
            $validated['user_id'] = $user->id;
            $validated['phones'] = json_encode($validated['phones']);
            $student = Student::create($validated);
    
            if ($request->hasFile('image')) {
                $student->image = $request->file('image')->store('students');
                $student->save();
            }
    
            $courseId = $request->course_id;
    
            // ✅ منطق الجلسة / الكورس
            $registeredToSession = false;
    
            if ($request->filled('course_session_id')) {
                $session = CourseSession::find($request->course_session_id);
                if ($session) {
                    $sessionStart = \Carbon\Carbon::parse($session->start_date);
                    $now = now();
                    if ($now->lessThanOrEqualTo($sessionStart->copy()->addDays(5))) {
                        CourseSessionStudent::updateOrCreate([
                            'student_id' => $student->id,
                            'course_session_id' => $session->id,
                        ], ['status' => 'active']);
                        $registeredToSession = true;
                        $courseId = $session->course_id;
                    }
                }
            }
    
            if (!$registeredToSession) {
                CourseStudent::updateOrCreate([
                    'student_id' => $student->id,
                    'course_id' => $courseId,
                ], [
                    'register_at' => now(),
                    'study_time' => $request->study_time ?? '8-10',
                ]);
            }
    
            // ✅ السعر من جدول course_prices
            $coursePrice = CoursePrice::where('course_id', $courseId)
                ->orderByDesc('id')
                ->first();
    
            if (!$coursePrice) {
                return back()->withErrors(['course_id' => '⚠️ لا يوجد سعر محدد لهذا الكورس.'])->withInput();
            }
    
            // ✅ إنشاء الفاتورة
            $invoice = Invoice::create([
                'student_id' => $student->id,
                'amount' => $coursePrice->price,
                'status' => ($request->amount_paid >= $coursePrice->price) ? '1' : '0',
                'invoice_number' => '25' . time(),
                'invoice_details' => "رسوم الكورس: " . Course::find($courseId)?->course_name,
                'due_date' => now(),
                'paid_at' => ($request->amount_paid >= $coursePrice->price) ? now() : null,
                'payment_sources_id' => PaymentSource::where('name', $request->payment_method)->value('id'),
            ]);
    
            // ✅ إنشاء الدفع
            Payment::create([
                'student_id' => $student->id,
                'invoice_id' => $invoice->id,
                'amount' => $request->amount_paid,
                'payment_date' => now(),
                'status' => 'completed',
                'payment_sources_id' => $invoice->payment_sources_id,
            ]);
    
            DB::commit();
            return redirect()->route('students.invoice', $student->id)->with('success', 'تم تسجيل الطالب بنجاح!');    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => '❌ حدث خطأ أثناء الحفظ: ' . $e->getMessage()])->withInput();
        }
    }
    public function showInvoice($studentId)
    {
        $student = Student::findOrFail($studentId);
        $invoice = $student->invoices()->latest()->first();
        $payment = $invoice ? $invoice->payments()->latest()->first() : null;
    
        return view('admin.pages.students.invoice', compact('student', 'invoice', 'payment'));
    }
    
    public function printInvoice($studentId)
    {
        // جلب الطالب
        $student = Student::findOrFail($studentId);
    
        // جلب أحدث فاتورة للطالب
        $invoice = $student->invoices()->latest()->first();
    
        // التحقق من وجود الفاتورة
        if (!$invoice) {
            return redirect()->back()->with('error', '⚠️ لا توجد فاتورة لطباعتها.');
        }
    
        // جلب آخر عملية دفع مرتبطة بهذه الفاتورة (إن وجدت)
        $payment = $invoice->payments()->latest()->first();
    
        // عرض صفحة الطباعة
        return view('admin.pages.students.print_invoice', compact('student', 'invoice', 'payment'));
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
            'phones' => 'nullable|array',
            'phones.*' => 'string|max:20',            'gender' => 'required|in:male,female',
            'qualification' => 'nullable|max:150',
            'birth_date' => 'required|date',
            'birth_place' => 'required|max:150',
            'address' => 'required|max:300',
            'email' => 'nullable|email|unique:students,email,'.$id,
            'department_id' => 'required|exists:departments,id',
            'course_id' => 'nullable|exists:courses,id',
            'course_session_id' => 'nullable|exists:course_sessions,id',
            'state' => 'required|in:active,inactive,suspended,expelled,graduated',
        ]);

        // 🔹 جلب الطالب وتحديث البيانات
        $student = Student::findOrFail($id);
        $student->update($validated);

        // 🔹 تحديث الكورسات والجلسات
        if ($request->filled('course_session_id')) {
            // حذف أي تسجيل قديم للطالب في الجلسات
            CourseSessionStudent::where('student_id', $student->id)->delete();

            // تسجيل الطالب في الجلسة الجديدة
            CourseSessionStudent::create([
                'student_id' => $student->id,
                'course_session_id' => $request->course_session_id,
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
