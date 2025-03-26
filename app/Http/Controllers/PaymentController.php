<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['student', 'invoice', 'source'])->latest()->paginate(20);
        return view('admin.pages.payments.index', compact('payments'));
    }
    public function show(Payment $payment)
    {
        $student = $payment->student;
        $invoice = $payment->invoice;
        $source = $payment->source; // assuming 'source' is the relationship for PaymentSource
    
        return view('admin.pages.payments.show', compact('payment', 'student', 'invoice', 'source'));
    }
    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // التحقق من صحة المدخلات
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'session_id' => 'nullable|exists:course_sessions,id',
            'invoice_id' => 'required|exists:invoices,id',
            'status' => 'required|in:pending,completed,failed',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_sources_id' => 'nullable|exists:payment_sources,id',
        ]);

        // البحث عن الفاتورة
        $invoice = Invoice::findOrFail($request->invoice_id);
        if ($invoice->status == 'paid') {
            return response()->json(['error' => 'Invoice already paid'], 400);
        }

        // إنشاء سجل الدفع
        $payment = Payment::create([
            'student_id' => $request->student_id,
            'session_id' => $request->session_id,
            'invoice_id' => $request->invoice_id,
            'status' => 'completed',  // تحديد الحالة هنا حسب حالة الدفع
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'payment_sources_id' => $request->payment_sources_id,  // إذا كان هناك مصدر دفع
        ]);

        // تحديث حالة الفاتورة
        $invoice->update(['status' => 'paid']);

        // إرسال رد بنجاح
        return response()->json(['message' => 'Payment successfully recorded'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(payment $payment)
    {
        //
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
