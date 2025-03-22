<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentPaymentController extends Controller
{
    public function show()
    {
        $student = Auth::user()->student;

        // الحصول على أحدث فاتورة للطالب
        $invoice = \App\Models\Invoice::with(['items', 'payments.source']) // مهم جلب العلاقات
        ->where('student_id', $student->id)
            ->latest('due_date')
            ->first();

        if (!$invoice) {
            return view('dashboard-Student.payment', [
                'student' => $student,
                'invoice' => null,
                'invoice_items' => [],
                'payment' => null,
                'payment_source' => null,
            ]);
        }

        // بنود الفاتورة (مثال: رسوم دراسة - نقل)
        $invoice_items = DB::table('invoice_items')
            ->where('invoice_id', $invoice->id)
            ->get();

        // تفاصيل السداد
        $payment = DB::table('payments')
            ->where('invoice_id', $invoice->id)
            ->first();

        // جهة الدفع
        $payment_source = $payment
            ? DB::table('payment_sources')->where('id', $payment->payment_source_id)->first()
            : null;

        return view('dashboard-Student.payment', [
            'student' => $student,
            'invoice' => $invoice,
            'invoice_items' => $invoice_items,
            'payment' => $payment,
            'payment_source' => $payment_source,
        ]);
    }
}
