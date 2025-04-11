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
        $q->where('students.id', $searchTerm)
          ->orWhere('students.student_name_ar', 'LIKE', "%{$searchTerm}%")
          ->orWhere('students.student_name_en', 'LIKE', "%{$searchTerm}%");
    });
}

// 🔍 فلترة الطلاب بناءً على القسم
if ($request->filled('department_id')) {
    $query->whereHas('courses', function ($q) use ($request) {
        $q->where('courses.department_id', $request->department_id);
    });
}

// 🔍 فلترة الطلاب بناءً على الدورة
if ($request->filled('course_id')) {
    $query->whereHas('courses', function ($q) use ($request) {
        $q->where('courses.id', $request->course_id);
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
        $departments = Department::with(['courses' => function ($query) {
            $query->orderBy('id')->limit(1);
        }])->get();
        $paymentSources = PaymentSource::where('status', 'active')->get();

        return view('admin.pages.students.create', compact('departments', 'paymentSources'));
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
            'payment_method' => 'required|exists:payment_sources,name',  // Validate payment method from the payment_sources table
        ]);

        DB::beginTransaction();

        try {
            // ✅ إنشاء المستخدم
            $email = $validated['email'] ?? strtolower(str_replace(' ', '_', $validated['student_name_en'])) . '@gmail.com';
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $validated['student_name_ar'], 'password' => bcrypt('123456')]
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
            $payment = Payment::create([
                'student_id' => $student->id,
                'course_id' => $courseId,
                'total_amount' => $coursePrice->price,
                'status' => ($request->amount_paid >= $coursePrice->price) ? 'paid' : 'unpaid',
            ]);
                  // ✅ إنشاء الفاتورة في جدول invoices
            $invoice = Invoice::create([
            'student_id' => $student->id,
            'payment_id' => $payment->id, // ربط الفاتورة بالدفع
            'amount' => $request->amount_paid,
            'status' => '1',
            'invoice_number' => '25' . time(),
            'invoice_details' => "رسوم الكورس: " . Course::find($courseId)->course_name,
            'due_date' => now()->addDays(30),
            'paid_at' =>  now(),
            'payment_sources_id' => PaymentSource::where('name', $request->payment_method)->value('id'),
        ]);
        // ✅ إضافة الدفع للفاتورة
        $payment->update([
            'status' => ($request->amount_paid >= $coursePrice->price) ? 'paid' : 'partial',
        ]);

            DB::commit();
            return redirect()->route('students.invoice', $student->id)->with('success', 'تم تسجيل الطالب بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => '❌ حدث خطأ أثناء الحفظ: ' . $e->getMessage()])->withInput();
        }
    }

    public function showRegisterNextCourseForm()
{
    $courses = Course::all();
    $paymentMethods = PaymentSource::all();

    return view('admin.pages.students.register_next', compact('courses', 'paymentMethods'));
}

    public function registerNextCourse(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'course_id' => 'required|exists:courses,id',
        'course_session_id' => 'nullable|exists:course_sessions,id',
        'study_time' => 'nullable|in:8-10,10-12,12-2,2-4,4-6',
        'amount_paid' => 'required|numeric|min:0',
        'payment_method' => 'required|exists:payment_sources,name',
    ]);

    DB::beginTransaction();

    try {
        $student = Student::findOrFail($request->student_id);
        $courseId = $request->course_id;

        $registeredToSession = false;

        // ✅ تسجيله في الجلسة إن وجدت
        if ($request->filled('course_session_id')) {
            $session = CourseSession::find($request->course_session_id);
            if ($session) {
                $start = \Carbon\Carbon::parse($session->start_date);
                if (now()->lte($start->copy()->addDays(5))) {
                    CourseSessionStudent::updateOrCreate([
                        'student_id' => $student->id,
                        'course_session_id' => $session->id,
                    ], ['status' => 'active']);
                    $courseId = $session->course_id;
                    $registeredToSession = true;
                }
            }
        }

        // ✅ إذا لم تكن هناك جلسة، سجله في الكورس فقط
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
        $coursePrice = CoursePrice::where('course_id', $courseId)->latest()->first();

        if (!$coursePrice) {
            return back()->withErrors(['course_id' => '⚠️ لا يوجد سعر محدد لهذا الكورس.']);
        }

        // ✅ إنشاء الدفع
        $payment = Payment::create([
            'student_id' => $student->id,
            'course_id' => $courseId,
            'total_amount' => $coursePrice->price,
            'status' => ($request->amount_paid >= $coursePrice->price) ? 'paid' : 'partial',
        ]);

        // ✅ إنشاء الفاتورة
        $invoice = Invoice::create([
            'student_id' => $student->id,
            'payment_id' => $payment->id,
            'amount' => $request->amount_paid,
            'status' => '1',
            'invoice_number' => '25' . time(),
            'invoice_details' => 'رسوم الكورس: ' . Course::find($courseId)->course_name,
            'due_date' => now()->addDays(30),
            'paid_at' => now(),
            'payment_sources_id' => PaymentSource::where('name', $request->payment_method)->value('id'),
        ]);

        DB::commit();

        return redirect()->route('students.invoice', $student->id)->with('success', '✅ تم تسجيل الطالب في الدورة الجديدة بنجاح!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => '❌ حدث خطأ: ' . $e->getMessage()]);
    }
}

    public function showInvoice($studentId)
    {
        // جلب الطالب من قاعدة البيانات
        $student = Student::findOrFail($studentId);

        // جلب الفاتورة الأخيرة المرتبطة بالطالب
        $invoice = $student->invoices()->latest()->first();

        // التحقق إذا كانت الفاتورة موجودة، ثم جلب الدفع المرتبط بها عبر payment_id
        $payment = $invoice ? Payment::find($invoice->payment_id) : null;

        // تمرير البيانات إلى الـ View
        return view('admin.pages.students.invoice', compact('student', 'invoice', 'payment'));
    }


    public function printInvoice($studentId)
    {
        // جلب الطالب من قاعدة البيانات
        $student = Student::findOrFail($studentId);

        // جلب أحدث فاتورة للطالب
        $invoice = $student->invoices()->latest()->first();

        // التحقق من وجود الفاتورة
        if (!$invoice) {
            return redirect()->back()->with('error', '⚠️ لا توجد فاتورة لطباعتها.');
        }

        // جلب الدفع المرتبط بالفاتورة باستخدام payment_id
        $payment = $invoice->payment;  // نستخدم payment بدلاً من payments()

        // عرض صفحة الطباعة
        return view('admin.pages.students.print_invoice', compact('student', 'invoice', 'payment'));
    }




    public function edit($id, Request $request)
    {
        // جلب بيانات الطالب من قاعدة البيانات
        $student = Student::with(['courses', 'sessions', 'payments', 'invoices'])->findOrFail($id);

        // تحديد القسم الذي سيتم تعديله (بيانات شخصية، أكاديمية، أو مالية)
        $section = $request->get('section', 'all'); // القيمة الافتراضية لتعديل الكل

        // جلب البيانات المرتبطة
        $departments = Department::all();
        $courses = Course::all();
        $sessions = CourseSession::all();
        $paymentSources = PaymentSource::all();

        return view('admin.pages.students.edit', compact(
            'student',
            'departments',
            'courses',
            'sessions',
            'paymentSources',
            'section'
        ));
    }




    public function update(Request $request, $id)
    {
        // تأكد من أن البيانات المرسلة صالحة
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
            'email' => 'nullable|email|unique:students,email,' . $id,
            'state' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
            'course_id' => 'required|exists:courses,id',
            'study_time' => 'nullable|in:8-10,10-12,12-2,2-4,4-6',
            'course_session_id' => 'nullable|exists:course_sessions,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|exists:payment_sources,name',
        ]);

        // بدء عملية المعاملة
        DB::beginTransaction();

        try {
            // جلب الطالب
            $student = Student::findOrFail($id);

            // تحديث بيانات المستخدم
            $user = $student->user;
            $user->name = $validated['student_name_ar'];
            $user->email = $validated['email'] ?? $user->email;
            $user->save();

            // تحديث بيانات الطالب
            $student->update($validated);

            // إذا كانت هناك صورة جديدة
            if ($request->hasFile('image')) {
                $student->image = $request->file('image')->store('students', 'public');
                $student->save();
            }

            // التعامل مع القسم والكورس والجلسة
            if ($request->section == 'academic' || $request->section == 'all') {
                // تحديث الكورس والجلسة
                $student->courses()->sync([$validated['course_id']]);
                if ($request->filled('course_session_id')) {
                    $student->sessions()->sync([$validated['course_session_id']]);
                }
            }

            // التعامل مع البيانات المالية
            if ($request->section == 'financial' || $request->section == 'all') {
                // تحديث الدفع والفاتورة
                $coursePrice = CoursePrice::where('course_id', $validated['course_id'])->latest()->first();

                if (!$coursePrice) {
                    return back()->withErrors(['course_id' => '⚠️ لا يوجد سعر محدد لهذا الكورس.'])->withInput();
                }

                // تحديث الدفع
                $payment = Payment::updateOrCreate(
                    ['student_id' => $student->id, 'course_id' => $validated['course_id']],
                    ['total_amount' => $coursePrice->price, 'status' => ($validated['amount_paid'] >= $coursePrice->price) ? 'paid' : 'unpaid']
                );

                // إنشاء أو تحديث الفاتورة
                $invoice = Invoice::updateOrCreate(
                    ['student_id' => $student->id, 'payment_id' => $payment->id],
                    [
                        'amount' => $validated['amount_paid'],
                        'status' => '1',
                        'invoice_number' => '25' . time(),
                        'invoice_details' => "رسوم الكورس: " . Course::find($validated['course_id'])->course_name,
                        'due_date' => now()->addDays(30),
                        'paid_at' => ($validated['amount_paid'] >= $coursePrice->price) ? now() : null,
                        'payment_sources_id' => PaymentSource::where('name', $validated['payment_method'])->value('id'),
                    ]
                );

                // تحديث حالة الدفع
                $payment->update([
                    'status' => ($validated['amount_paid'] >= $coursePrice->price) ? 'paid' : 'partial',
                ]);
            }

            DB::commit();

            // إعادة التوجيه مع رسالة النجاح
            return redirect()->route('students.invoice', $student->id)->with('success', 'تم تحديث بيانات الطالب بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => '❌ حدث خطأ أثناء التحديث: ' . $e->getMessage()])->withInput();
        }
    }



    //حق صفحة التسجيل
        public function showRegistrationForm()
        {
            $departments = Department::with('courses')->get();
            return view('admin.pages.students.register', compact('departments'));
        }
        public function register(Request $request)
        {
            try {
                $request->merge(['payment_method' => 'البريد']);

                // ✅ تحقق من صحة البيانات
                $validated = $request->validate([
                    'student_name_ar' => 'required|string|max:255',
                    'student_name_en' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email|unique:students,email',
                    'phone' => 'required|array',
                    'phone.*' => 'string|max:20',
                    'address' => 'required|string',
                    'gender' => 'required|in:Male,Female',
                    'qualification' => 'required|string',
                    'birth_date' => 'required|date',
                    'birth_place' => 'required|string',
                    'image' => 'nullable|image|max:2048',
                    'department' => 'required|exists:departments,id',
                    'course_id' => 'required|exists:courses,id',
                    'course_session_id' => 'nullable|exists:course_sessions,id',
                    'time' => 'nullable|in:8-10,10-12,2-4,4-6',
                    'amount_paid' => 'required|numeric|min:0',
                    'payment_method' => 'required|in:المعهد,البريد'
                ]);

                DB::beginTransaction();



                // Store phone numbers as JSON
                $validated['phones'] = json_encode($validated['phone']);
                // ✅ رفع الصورة
                $imagePath = $request->hasFile('image') ? $request->file('image')->store('students/images', 'public') : null;

                // ✅ إنشاء المستخدم
                $user = User::create([
                    'name' => $validated['student_name_en'],
                    'email' => $validated['email'],
                    'password' => Hash::make('123456'),
                ]);
                $user->assignRole('student');

                // ✅ إنشاء الطالب
                $student = Student::create([
                    'user_id' => $user->id,
                    'student_name_ar' => $validated['student_name_ar'],
                    'student_name_en' => $validated['student_name_en'],
                    'email' => $validated['email'],
                    'phones' => $validated['phones'],
                    'address' => $validated['address'],
                    'gender' => $validated['gender'],
                    'qualification' => $validated['qualification'],
                    'birth_date' => $validated['birth_date'],
                    'birth_place' => $validated['birth_place'],
                    'image' => $imagePath,
                    'state' => '0',
                ]);

                $courseId = $validated['course_id'];
                $registeredToSession = false;

                // ✅ تسجيل في الجلسة إن وجدت وصالحة
                if ($request->filled('course_session_id')) {
                    $session = CourseSession::find($validated['course_session_id']);
                    if ($session) {
                        $sessionStart = \Carbon\Carbon::parse($session->start_date);
                        if (now()->lessThanOrEqualTo($sessionStart->copy()->addDays(5))) {
                            CourseSessionStudent::updateOrCreate([
                                'student_id' => $student->id,
                                'course_session_id' => $session->id,
                            ], ['status' => 'active']);
                            $registeredToSession = true;
                            $courseId = $session->course_id;
                        }
                    }
                }

                // ✅ تسجيل في الكورس إن لم يسجل في جلسة
                if (!$registeredToSession) {
                    CourseStudent::create([
                        'student_id' => $student->id,
                        'course_id' => $courseId,
                        'time' => $validated['time'] ?? '8-10',
                        'status' => 'waiting',
                    ]);
                }

                // ✅ جلب السعر
                $coursePrice = CoursePrice::where('course_id', $courseId)->latest()->first();
                if (!$coursePrice) {
                    throw new \Exception('⚠️ لا يوجد سعر للكورس المحدد.');
                }

                if ($validated['amount_paid'] > $coursePrice->price) {
                    return back()->withErrors([
                        'amount_paid' => '⚠️ المبلغ المدفوع لا يمكن أن يتجاوز سعر الكورس: ' . $coursePrice->price . ' ريال'
                    ])->withInput();
                }

                // ✅ إنشاء الفاتورة
                $payment = Payment::create([
                    'student_id' => $student->id,
                    'course_id' => $courseId,
                    'total_amount' => $coursePrice->price,
                    'status' => 'unpaid',
                    ]);
                      // ✅ إنشاء الفاتورة في جدول invoices
                $invoice = Invoice::create([
                'student_id' => $student->id,
                'payment_id' => $payment->id, // ربط الفاتورة بالدفع
                'amount' => $request->amount_paid,
                'status' => '0',
                'invoice_number' => '25' . time(),
                'invoice_details' => "رسوم الكورس: " . Course::find($courseId)->course_name,
                'due_date' => now()->addDays(30),
                'paid_at' => null,
                'payment_sources_id' => PaymentSource::where('name', $request->payment_method)->value('id'),
            ]);
        // التحقق من حالة الفاتورة في جدول الحوافظ (Invoices)
        $existingInvoice = Invoice::where('student_id', $student->id)
            ->where('status', 1) // تحقق من أن الفاتورة قد تم تسديدها بالكامل
            ->first();

            if ($existingInvoice) {
                // إذا كانت الفاتورة قد تم تسديدها بالكامل، نقوم بتحديث حالة الدفع
                $invoice->update([
                    'status' => '1',  // "مدفوع"
                ]);
                $payment->update([
                    'status' => 'paid', // تحديث حالة الدفع إلى "مدفوع"
                ]);
            }

                DB::commit();

                return redirect()->route('students.invoice.view', $invoice->id)
                    ->with('success', '✅ تم التسجيل بنجاح، وتم إصدار الحافظة  !');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('❌ فشل في تسجيل الطالب: ' . $e->getMessage());
                return back()->withErrors(['error' => 'حدث خطأ أثناء التسجيل: ' . $e->getMessage()])->withInput();
            }
        }
        public function showInvoiceConfirmation($invoiceId)
        {
            // جلب الفاتورة مع الطالب
            $invoice = Invoice::with('student')->findOrFail($invoiceId);

            // جلب الدفع المرتبط بالفاتورة باستخدام payment_id
            $payment = Payment::where('id', $invoice->payment_id)->first(); // الآن نستخدم payment_id في invoices

            // التحقق إذا كانت الفاتورة مدفوعة بالكامل
            if ($invoice->status == 1) { // 1 يعني "مدفوع"
                $payment->status = 'paid'; // تحديث حالة الدفع إلى "مدفوع"
                $payment->save(); // حفظ التغيير
            }

            return view('admin.pages.students.invoicestudent', compact('invoice', 'payment'));
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
    public function transferStudent()
{
    // عرض صفحة تحديث الطالب إلى الدورة التالية
    return view('admin.pages.students.transfer');
}

public function processTransfer(Request $request, $studentId)
{
    $validated = $request->validate([
        'amount_paid' => 'required|numeric|min:0',
    ]);

    // الحصول على الطالب
    $student = Student::findOrFail($studentId);

    // الحصول على الدورة الحالية
    $currentCourse = $student->courses()->latest()->first();

    // العثور على الدورة التالية بناءً على ترتيب ID
    $nextCourse = Course::where('id', '>', $currentCourse->id)->orderBy('id')->first();

    if (!$nextCourse) {
        return back()->withErrors(['error' => 'لا توجد دورة تالية للتسجيل.']);
    }

    // تسجيل الطالب في الدورة التالية
    CourseStudent::updateOrCreate([
        'student_id' => $student->id,
        'course_id' => $nextCourse->id,
    ], [
        'register_at' => now(),
        'status' => 'registered',  // حالة الطالب المسجل في الدورة التالية
    ]);

    // الحصول على السعر من جدول CoursePrice
    $coursePrice = CoursePrice::where('course_id', $nextCourse->id)->latest()->first();

    if (!$coursePrice) {
        return back()->withErrors(['error' => 'لا يوجد سعر محدد لهذه الدورة.']);
    }

    // إنشاء الدفع
    $payment = Payment::create([
        'student_id' => $student->id,
        'course_id' => $nextCourse->id,
        'total_amount' => $coursePrice->price,
        'status' => 'unpaid',
    ]);

    // إنشاء الفاتورة
    $invoice = Invoice::create([
        'student_id' => $student->id,
        'payment_id' => $payment->id,
        'amount' => $validated['amount_paid'],
        'status' => '0',
        'invoice_number' => 'INV' . time(),
        'invoice_details' => "رسوم الدورة: " . $nextCourse->course_name,
        'due_date' => now()->addDays(30),
    ]);

    // تحديث حالة الدفع بناءً على المبلغ المدفوع
    $payment->update([
        'status' => ($validated['amount_paid'] >= $coursePrice->price) ? 'paid' : 'partial',
    ]);

    return redirect()->route('students.transfer')->with('success', 'تم انتقال الطالب إلى الدورة التالية وتسجيل الفاتورة بنجاح!');
}
public function search(Request $request)
{
    $query = $request->query('query');

    if ($query) {
        $students = Student::where('student_name_ar', 'like', '%' . $query . '%')
                            ->get();
        return response()->json($students);
    }

    return response()->json([]);
}



public function showFormteacher()


{    // الحصول على بيانات الموظف (الاستاذ)
    $employee = auth()->user()->employee; // إذا كان لديك علاقة مع المستخدم يمكن استخدام auth()

    $departments = Department::where('state', 1)->get();

     // تعريف المتغيرات حتى لا تظهر أخطاء
     $courses = [];
     $sessions = [];
     $students = [];
     $noStudentsMessage = null;
     $session = null;





    return view('Teacher-dashboard.T-students', compact(
        'departments', 'courses', 'sessions', 'students', 'noStudentsMessage', 'session','employee'
    ));
}


public function showStudentsForTeacher(Request $request)
{
    $employee = auth()->user()->employee;

    $departments = Department::where('state', 1)->get();
    $courses = [];
    $sessions = [];
    $students = collect();
    $noStudentsMessage = null;
    $session = null;

    if ($request->filled('department_id')) {
        $courses = Course::where('department_id', $request->department_id)
            ->whereHas('sessions', function ($query) use ($employee) {
                $query->where('employee_id', $employee->id);
            })
            ->get();
        
        if ($courses->isEmpty()) {
            session()->flash('error', 'لا يوجد كورسات يدرسها المدرس في هذا القسم.');
        }
    }
    

    // جلب الجلسات إذا تم اختيار كورس
    if ($request->filled('course_id')) {
        $sessions = CourseSession::where('course_id', $request->course_id)
            ->where('employee_id', $employee->id)
            ->get();
    }

    // جلب الطلاب إذا تم اختيار جلسة
    if ($request->filled('session_id')) {
        $session = CourseSession::with('course')->find($request->session_id);

        $students = Student::join('course_session_students', 'students.id', '=', 'course_session_students.student_id')
            ->where('course_session_students.course_session_id', $request->session_id)
            ->select('students.*')
            ->with([
                'degrees' => function ($query) use ($request) {
                    $query->where('course_session_id', $request->session_id);
                },
                'courseSessions.course'
            ])
            ->get();

        $noStudentsMessage = $students->isEmpty() ? 'لا يوجد طلاب في هذه الجلسة.' : null;
    }

    return view('Teacher-dashboard.T-students', compact(
        'departments', 'courses', 'sessions', 'students', 'session', 'noStudentsMessage', 'employee'
    ));
}





public function getCoursesteacher($departmentId)
{
    $employeeId = auth()->user()->employee->id;

    // جلب الكورسات الخاصة بالقسم والنشطة التي يدرسها الاستاذ
    $courses = Course::where('department_id', $departmentId)
        ->where('state', 1) // التأكد من أن الكورسات نشطة
        ->whereHas('Sessions', function($q) use ($employeeId) {
            $q->where('employee_id', $employeeId)->where('state', 1); // فقط الجلسات التي يشرف عليها الاستاذ والنشطة
        })
        ->get();

    return response()->json($courses);
}


public function getSessionsteacher($courseId)
{
    $employeeId = auth()->user()->employee->id;

    // جلب الجلسات الخاصة بالكورس والتي يدرسها الاستاذ
    $sessions = CourseSession::where('course_id', $courseId)
        ->where('employee_id', $employeeId) // التأكد من أن الجلسات تخص الاستاذ
        ->where('state', 1) // التأكد من أن الجلسات نشطة
        ->get();

    return response()->json($sessions);
}


public function searchStudentst(Request $request)
{
    $query = Student::with(['course.department', 'sessions']); // جلب البيانات المرتبطة

    // إضافة التصفية بناءً على اسم الطالب
    if ($request->has('name') && $request->name) {
        $query->where('student_name_ar', 'like', '%' . $request->name . '%');
    }

    // إضافة التصفية بناءً على القسم
    if ($request->has('department_id') && $request->department_id) {
        $query->whereHas('course.department', function ($q) use ($request) {
            $q->where('id', $request->department_id);
        });
    }

    // إضافة التصفية بناءً على الكورس
    if ($request->has('course_id') && $request->course_id) {
        $query->where('course_id', $request->course_id);
    }

    // إضافة التصفية بناءً على الجلسة
    if ($request->has('session_id') && $request->session_id) {
        $query->where('session_id', $request->session_id);
    }

    // جلب الطلاب وفقًا للمعايير
    $students = $query->get();

    // إرجاع النتيجة كـ JSON
    return response()->json($students);
}






public function showStudentsteacher($sessionId)
{

        // الحصول على بيانات الموظف (الاستاذ)
        $employee = auth()->user()->employee; // إذا كان لديك علاقة مع المستخدم يمكن استخدام auth()

    $session = CourseSession::findOrFail($sessionId);
    $departments = Department::where('state', 1)->get();
    $courses = Course::where('department_id', request('department_id'))->get();
    $sessions = CourseSession::where('course_id', request('course_id'))
        ->where('employee_id', auth()->user()->employee->id)
        ->get();

    $students = Student::join('course_session_students', 'students.id', '=', 'course_session_students.student_id')
        ->where('course_session_students.course_session_id', $sessionId)
        ->select('students.*')
        ->with(['degrees' => function ($query) use ($sessionId) {
            $query->where('course_session_id', $sessionId);
        }, 'courseSessions.course']) // لتحميل الكورسات أيضًا
        ->get();

    $noStudentsMessage = $students->isEmpty() ? 'لا يوجد طلاب في هذه الجلسة.' : null;

    return view('Teacher-dashboard.T-students', compact(
        'departments', 'courses', 'sessions', 'session', 'students', 'noStudentsMessage','employee'
    ));
}







}
