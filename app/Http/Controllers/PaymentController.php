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

        $query = Student::query()
            ->with(['invoices', 'payments']) // علاقات لجلب الفواتير والمدفوعات
            ->withSum('invoices', 'amount')  // المبلغ الكلي المطلوب
            ->withSum('payments', 'amount'); // المبلغ المدفوع

        if ($sessionId) {
            $query->whereHas('sessions', function ($q) use ($sessionId) {
                $q->where('course_sessions.id', $sessionId);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('student_name_ar', 'like', "%$search%")
                  ->orWhere('student_name_en', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phones', 'like', "%$search%")
                  ->orWhere('id', 'like', "%$search%");
            });
        }

        $students = $query->get();

        $departments = Department::all();

        return view('admin.pages.payments.index', [
            'students'    => $students,
            'departments' => $departments,
        ]);
    }
    public function studentPaymentDetails($studentId)
    {
        $student = Student::with(['invoices.payments'])->findOrFail($studentId);
        return view('admin.pages.payments.details', compact('student'));
    }
    public function showInvoiceDetails($invoiceId)
    {
        $invoice = Invoice::with(['payments'])->findOrFail($invoiceId);
        return view('admin.pages.payments.invoice_show', compact('invoice'));
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
