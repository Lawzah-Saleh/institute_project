<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Invoice;

class StudentPaymentController extends Controller
{
    // Display the main page with options (Paid or Unpaid)
    public function index()
    {
        return view('dashboard-Student.payments.index');
    }

    // Display all paid invoices
    public function showPaid()
    {
        $student = Auth::user()->student;

        $paidInvoices = DB::table('invoices')
            ->where('student_id', $student->id)
            ->where('status', '1')
            ->orderBy('paid_at', 'desc')
            ->get();

        return view('dashboard-Student.payments.paid', compact('paidInvoices'));
    }

    // Display all unpaid invoices
    public function showUnpaid()
    {
        // Fetch the authenticated student
        $student = Auth::user()->student;

        // Get all unpaid invoices for the student
        $unpaidInvoices = DB::table('invoices')
            ->where('student_id', $student->id)
            ->where('status', '0')
            ->orderBy('due_date', 'desc')
            ->get();

        // Return the unpaid invoices page with the data
        return view('dashboard-Student.payments.unpaid', compact('unpaidInvoices'));
    }

    // Show the payment page for a specific invoice
 // Show the payment page for a specific invoice
 public function showPaymentPage($invoice_id)
 {
     // التحقق من الفاتورة
     $invoice = DB::table('invoices')
         ->where('id', $invoice_id)
         ->where('status', '0') // تأكد أن الفاتورة غير مدفوعة
         ->first();

     // التحقق من وجود الفاتورة
     if (!$invoice) {
         return redirect()->route('student.payment.unpaid')->with('error', 'Invoice not found or already paid.');
     }

    //  // التحقق من أن الفاتورة تخص الطالب الحالي
    //  if ($invoice->student_id != Auth::id()) {
    //      return redirect()->route('student.payment.unpaid')->with('error', 'This invoice does not belong to you!');
    //  }

     // عرض الصفحة مع تفاصيل الفاتورة
     return view('dashboard-Student.payments.pay', compact('invoice'));
 }
 public function storePayment(Request $request)
 {
     $request->validate([
         'amount' => 'required|numeric|min:1',
     ]);

     $invoice = Invoice::find($request->invoice_id);

     if (!$invoice) {
         return redirect()->route('dashboard-Student.payments.unpaid')->with('error', 'الفاتورة غير موجودة');
     }

     $invoice->payment_status = 'paid';
     $invoice->save();

     return redirect()->route('dashboard-Student.payments.paid')->with('success', 'تم الدفع بنجاح');
 }


   // Handle payment for an invoice
public function payInvoice(Request $request, $invoice_id)
{
    $request->validate([
        'payment_method' => 'required|string',
        'amount' => 'required|numeric|min:1',
    ]);

    $invoice = Invoice::find($invoice_id);

    if (!$invoice || $invoice->status != 'unpaid') {
        return redirect()->route('student.payment.unpaid')->with('error', 'Invoice not found or already paid.');
    }

    // Update the invoice payment details
    $invoice->status = 'paid';
    $invoice->payment_date = Carbon::now();
    $invoice->payment_method = $request->payment_method;
    $invoice->amount_paid = $request->amount;
    $invoice->save();

    // Insert the payment into the payments table
    DB::table('payments')->insert([
        'invoice_id' => $invoice_id,
        'student_id' => $invoice->student_id,
        'amount' => $request->amount,
        'payment_method' => $request->payment_method,
        'payment_date' => Carbon::now(),
    ]);

    return redirect()->route('student.payment.paid')->with('success', 'Payment successful!');
}

    // Mark an invoice as paid (for manual updates or administrative actions)
    public function markAsPaid($invoice_id)
    {
        $invoice = Invoice::find($invoice_id);

        if (!$invoice) {
            return redirect()->route('student.payments')->with('error', 'Invoice not found');
        }

        // Update the invoice status to "paid"
        $invoice->status = 'paid';
        $invoice->payment_date = Carbon::now();
        $invoice->save();

        return redirect()->route('student.payments')->with('success', 'Payment has been successfully marked as paid');
    }
}
