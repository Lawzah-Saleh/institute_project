{{-- resources/views/admin/pages/students/invoice.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'تأكيد الدفع والفاتورة')

@section('content')
<div class="container mt-4">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white text-center">
            <h4>تأكيد الدفع والفاتورة</h4>
        </div>

        <div class="card-body">
            <h5 class="mb-3">📄 معلومات الفاتورة</h5>
            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>رقم الفاتورة:</strong> {{ $invoice->invoice_number }}</li>
                <li class="list-group-item"><strong>اسم الطالب:</strong> {{ $invoice->student->student_name_ar }}</li>
                <li class="list-group-item"><strong>المبلغ الإجمالي:</strong> {{ number_format($payment->total_amount, 2) }} ريال</li>
                <li class="list-group-item"><strong>تاريخ الاستحقاق:</strong> {{ $invoice->due_date }}</li>
                <li class="list-group-item"><strong>حالة الفاتورة:</strong>
                    <span class="badge {{ $payment->status == 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $payment->status == 'paid' ? 'مدفوعة' : 'غير مدفوعة' }}
                    </span>
                </li>
            </ul>

            @if ($payment)
                <h5 class="mb-3">💵 تفاصيل الدفع</h5>
                <ul class="list-group mb-4">
                    <li class="list-group-item"><strong>المبلغ المدفوع:</strong> {{ number_format($invoice->amount, 2) }} ريال</li>
                    <li class="list-group-item"><strong>طريقة الدفع:</strong> {{ $invoice->paymentSource->name ?? 'غير محدد' }}</li>
                    <li class="list-group-item"><strong>حالة الدفع:</strong>
                        <span class="badge {{ $invoice->status == '1' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $invoice->status == '1' ? 'مكتمل' : 'قيد المعالجة' }}
                        </span>
                    </li>
                </ul>
            @endif

            <a href="{{ route('students.index') }}" class="btn btn-primary w-100">⬅️ العودة إلى قائمة الطلاب</a>
            @if ($payment)
            <div class="text-center mt-4">
                <a href="{{ route('students.invoice.print', $student->id) }}" class="btn btn-info">
                    🧾 إصدار سند
                </a>
            </div>
        @endif
        </div>

    </div>
</div>
@endsection
