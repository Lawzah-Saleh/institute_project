<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\Course;
use App\Models\Payment;
use App\Models\CoursePrice;
use App\Models\PaymentSource;

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
            ->where('status', 'unpaid')
            ->orderBy('due_date', 'desc')
            ->get();
                // Get total amount from payments table
    $payment = DB::table('payments')
    ->where('student_id', $student->id)
    ->first();
    
        // Get total amount from payments table
        $payment = DB::table('payments')
            ->where('student_id', $student->id)
            ->first();
    
        // If no payment record found, set total amount to 0
        $totalAmount = $payment ? $payment->total_amount : 0;
    
        // Calculate the total amount paid from invoices
        $totalPaid = DB::table('invoices')
            ->where('student_id', $student->id)
            ->where('status', '1')
            ->sum('amount');
    
        // Calculate the remaining amount
        $remainingAmount = $totalAmount - $totalPaid;
    
        // Return the unpaid invoices page with data
        return view('dashboard-Student.payments.unpaid', compact('unpaidInvoices', 'totalAmount', 'totalPaid', 'remainingAmount','payment'));
    }

    // Show the payment page for a specific invoice
 // Show the payment page for a specific invoice
 public function payInvoice($paymentId)
{
    // Fetch the payment record by ID
    $payment = Payment::find($paymentId);
    
    if (!$payment) {
        return redirect()->back()->with('error', 'الدفع غير موجود.');
    }

    // Fetch available payment sources
    $paymentSources = PaymentSource::all(); // Fetch payment sources from the database

    // Return the view with the payment data and payment sources
    return view('dashboard-Student.payments.pay', compact('payment', 'paymentSources'));
}
public function processPayment(Request $request, $paymentId)
{
    // Validate the form data
    $validated = $request->validate([
        'amount_paid' => 'required|numeric|min:0',
        'payment_sources_id' => 'required|exists:payment_sources,id',
    ]);

    // Fetch the payment record by ID
    $payment = Payment::find($paymentId);

    if (!$payment) {
        return redirect()->back()->with('error', 'الدفع غير موجود.');
    }

    // Check if the amount paid does not exceed the total amount
    if ($validated['amount_paid'] > $payment->total_amount) {
        return back()->with('error', 'المبلغ المدفوع لا يمكن أن يتجاوز المبلغ الكلي.');
    }

    // Create a new invoice for the payment
    $invoiceNumber = 'INV-' . time();  // Generate a new invoice number
    $dueDate = Carbon::now()->addDays(30); // Set due date (e.g., 30 days from now)

    $invoice = Invoice::create([
        'student_id' => $payment->student_id,
        'payment_id' => $payment->id,
        'payment_sources_id' => $validated['payment_sources_id'],
        'amount' => $validated['amount_paid'],
        'status' => 0,  // Paid
        'invoice_number' => $invoiceNumber,
        'invoice_details' => 'دفع رسوم الكورس',
        'due_date' =>now()->addDays(30),
        'paid_at' => null,
    ]);

    // Update the payment status
    $payment->status = 'paid';
    $payment->save();

    // Redirect back with success message
    return redirect()->route('student.payment.paid', $payment->student_id)->with('success', 'تم الدفع بنجاح!');
}





 // جلب السعر بناءً على الدورة المختارة
 public function getCoursePrice($courseId)
 {
     $coursePrice = CoursePrice::where('course_id', $courseId)->latest()->first();
     if ($coursePrice) {
         return response()->json([
             'price' => $coursePrice->price
         ]);
     }

     return response()->json(['error' => 'Price not found'], 404);
 }


   // Handle payment for an invoice

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
