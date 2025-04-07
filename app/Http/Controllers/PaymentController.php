<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\CourseSessionStudent;
use App\Models\Student;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
    public function studentPaymentDetails($studentId)
    {
        // تأكد من تحميل الفواتير مع المدفوعات
        $student = Student::with(['payments.invoice'])->findOrFail($studentId);
    // حساب المبلغ الكلي المدفوع
    $totalPayments = $student->payments->sum('total_amount');

    // حساب المبلغ الكلي للطالب (من الفواتير)
    $totalAmount = $student->invoices->sum('amount');

    // حساب المبلغ المتبقي
    $remainingAmount = $totalPayments - $totalAmount ;

    return view('admin.pages.payments.details', compact('student', 'totalPayments', 'remainingAmount'));
    }

    public function showInvoiceDetails($paymentId)
    {
        // Retrieve the payment with its associated invoices (assuming payment model has an invoices() relationship)
        $payment = Payment::with('invoices')->findOrFail($paymentId);

        // Pass the payment and its invoices to the view
        return view('admin.pages.payments.invoice_show', compact('payment'));
    }




    // 📄 عرض صفحة الدفع للطالب
    public function create()
    {
        // Get all students
        $students = Student::all();

        // Return the create payment view
        return view('admin.pages.payments.create', compact('students'));
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



    // Store the payment
    public function storePayment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'invoice_id' => 'required|exists:invoices,id',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        // Store the payment
        $payment = new Payment();
        $payment->student_id = $request->student_id;
        $payment->invoice_id = $request->invoice_id;
        $payment->amount = $request->amount_paid;
        $payment->payment_date = Carbon::now(); // Add the current date and time
        $payment->save();

        // Update the invoice's total paid amount
        $invoice = Invoice::find($request->invoice_id);
        $invoice->amount = $invoice->payments()->sum('amount');
        $invoice->save();

        // Redirect with success message
        return redirect()->route('admin.payments.index')->with('success', 'تم إضافة الدفع بنجاح');
    }
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'invoice_id' => 'required|exists:invoices,id',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        // Store the payment
        $payment = new Payment();
        $payment->student_id = $request->student_id;
        $payment->invoice_id = $request->invoice_id;
        $payment->amount = $request->amount_paid;
        $payment->payment_date = Carbon::now(); // Add the current date and time
        $payment->save();

        // Update the invoice's total paid amount
        $invoice = Invoice::find($request->invoice_id);
        $invoice->amount = $invoice->payments()->sum('amount');
        $invoice->save();

        // Redirect with success message
        return redirect()->route('admin.payments.index')->with('success', 'تم إضافة الدفع بنجاح');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(payment $payment)
    {
        //
    }
}
