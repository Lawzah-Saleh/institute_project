<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\CourseSessionStudent;
use App\Models\Student;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $departmentId = $request->input('department_id');
        $courseId     = $request->input('course_id');
        $sessionId    = $request->input('session_id');
        $search       = $request->input('search');

        // Start the query for students
        $query = Student::query()
            ->with(['invoices', 'payments']) // Relationships to fetch invoices and payments
            ->withSum('invoices', 'amount')  // Total amount required from invoices
            ->withSum('payments', 'total_amount'); // Paid amount from payments

        // Filter by session
        if ($sessionId) {
            $query->whereHas('sessions', function ($q) use ($sessionId) {
                $q->where('course_sessions.id', $sessionId);
            });
        }

        // Filter by search term
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('student_name_ar', 'like', "%$search%")
                  ->orWhere('student_name_en', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phones', 'like', "%$search%")
                  ->orWhere('id', 'like', "%$search%");
            });
        }

        // Filter by department
        if ($departmentId) {
            $query->whereHas('courses', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        // Get the students
        $students = $query->get();

        // Get all departments
        $departments = Department::all();

        // Get courses if department_id is set
        $courses = $departmentId ? Course::where('department_id', $departmentId)->get() : [];

        // Return the view with students, departments, and courses
        return view('admin.pages.payments.index', [
            'students'    => $students,
            'departments' => $departments,
            'courses'     => $courses, // Pass the filtered courses
        ]);
    }
    public function studentPaymentDetails(Request $request, $studentId)
    {
        $courseId = $request->input('course_id');

        // جلب بيانات الطالب مع العلاقات
        $student = Student::with(['payments.course', 'payments.invoices'])
                    ->findOrFail($studentId);

        $payment = null;
        $totalAmount = 0;
        $totalPayments = 0;
        $remainingAmount = 0;

        // إذا تم اختيار دورة
        if ($courseId) {
            $payment = $student->payments->where('course_id', $courseId)->first();

            if ($payment) {
                // المبلغ المطلوب من جدول الدفع
                $totalAmount = $payment->total_amount;

                // مجموع المدفوع من الفواتير المرتبطة
                $totalPayments = $payment->invoices->sum(function ($invoice) {
                    return $invoice->status == 1 ? $invoice->amount : 0;
                });

                // المتبقي = المبلغ المطلوب - المدفوع
                $remainingAmount = $totalAmount - $totalPayments;
            }
        }

        return view('admin.pages.payments.details', [
            'student'         => $student,
            'payment'         => $payment,
            'totalAmount'     => $totalAmount,
            'totalPayments'   => $totalPayments, // ✅ هذا السطر مهم
            'remainingAmount' => $remainingAmount,
        ]);
    }
    


    public function showInvoiceDetails($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        return view('admin.pages.payments.invoice_show', compact('invoice'));
    }



    public function getStudentDetails($studentId)
    {
        // Check if the student exists and load related data
        $student = Student::with(['payments.invoices'])->findOrFail($studentId);

        // Return the view with student data
        return view('admin.pages.payments.student_details', compact('student'));
    }




    // 📄 عرض صفحة الدفع للطالب
    public function create()
    {
        $students = Student::select('id', 'student_name_ar', 'email')->get();
        $paymentSources = PaymentSource::where('status', 'active')->get();

        return view('admin.pages.payments.add_payment', [
            'students' => $students,
            'paymentSources' => $paymentSources,
        ]);
    }

    /**
     * تخزين الدفع
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'         => 'required|exists:students,id',
            'payment_amount'     => 'required|numeric|min:0.01',
            'payment_sources_id' => 'required|exists:payment_sources,id',
            'payment_date'       => 'required|date',
        ]);

        $student = Student::with('invoices')->findOrFail($validated['student_id']);
        $totalDue = $student->invoices->sum('amount');

        // إنشاء سجل الدفع أو جلب الموجود
        $payment = Payment::firstOrCreate(
            ['student_id' => $student->id],
            ['total_amount' => 0, 'status' => 'unpaid']
        );


        // إنشاء فاتورة لهذه الدفعة
        $invoice = Invoice::create([
            'student_id'         => $student->id,
            'payment_id'         => $payment->id,
            'payment_sources_id' => $validated['payment_sources_id'],
            'amount'             => $validated['payment_amount'],
            'invoice_number'     => '25' . time(),
            'invoice_details'    => 'دفعة جديدة',
            'due_date'           => now()->addDays(30),
            'paid_at'            => $validated['payment_date'],
            'status'             => true,
        ]);
            // حساب المبلغ المدفوع
    $paidAmount = $payment->invoices->sum('amount');

    // تحديث حالة الدفع بناءً على المبلغ المدفوع
    if ($paidAmount >= $payment->total_amount) {
        $payment->status = 'paid'; // المدفوع بالكامل
    } elseif ($paidAmount > 0) {
        $payment->status = 'partial'; // المدفوع جزئياً
    } else {
        $payment->status = 'unpaid'; // غير مدفوع
    }

    // حفظ الحالة الجديدة في الدفع
    $payment->save();

        return redirect()->route('payments.invoice.show', $invoice->id)->with('success', 'تم إضافة الدفع بنجاح ✅');
    }
    public function showInvoice($id)
{
    $invoice = Invoice::with(['student', 'paymentSource'])->findOrFail($id);

    return view('admin.pages.payments.invoice_show', compact('invoice'));
}
public function downloadInvoice($id)
{
    // العثور على الفاتورة بناءً على الرقم التعريفي
    $invoice = Invoice::findOrFail($id);

    // تحميل الفاتورة كـ PDF باستخدام DomPDF
    $pdf = PDF::loadView('admin.pages.payments.invoice_pdf', compact('invoice'));

    // تحميل الفاتورة كـ PDF
    return $pdf->download('invoice_' . $invoice->invoice_number . '.pdf');
}

  // في الـ PaymentController
 // في PaymentController.php
 public function search(Request $request)
 {
     $search = $request->input('search_student');
     $students = Student::where('student_name_ar', 'like', "%$search%")
                        ->orWhere('student_name_en', 'like', "%$search%")
                        ->orWhere('id', $search)
                        ->get(); // استرجاع جميع الطلاب الذين تطابق بياناتهم مع النص المدخل

     return response()->json($students); // إرجاع البيانات بصيغة JSON
 }


  public function show($studentId)
  {
      $student = Student::findOrFail($studentId);
      return view('admin.pages.payments.details', compact('student'));
  }

  public function showDetails($studentId)
  {
      $student = Student::findOrFail($studentId);
      return view('admin.pages.payments.detailstopay', compact('student'));
  }

  public function edit($id)
  {
      $payment = Payment::with('student', 'course')->findOrFail($id);
      return view('admin.pages.payments.edit', compact('payment'));
  }

  public function update(Request $request, $id)
  {
      $request->validate([
          'total_amount' => 'required|numeric|min:0',
      ]);

      $payment = Payment::findOrFail($id);
      $payment->total_amount = $request->total_amount;

      // تحديث الحالة تلقائيًا إذا تم السداد بالكامل
      $paid = $payment->invoices()->sum('amount');
      if ($paid >= $request->total_amount) {
          $payment->status = 'paid';
      } elseif ($paid > 0) {
          $payment->status = 'partial';
      } else {
          $payment->status = 'unpaid';
      }

      $payment->save();

      return redirect()->route('admin.student.payment.details', $payment->student_id)
                       ->with('success', 'تم تحديث رسوم الطالب بنجاح ✅');
  }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(payment $payment)
    {
        //
    }
}
