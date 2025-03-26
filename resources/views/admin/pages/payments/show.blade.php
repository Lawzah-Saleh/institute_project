@extends('admin.layouts.app')

@section('title', 'تفاصيل الدفع')

@section('content')
<div class="container">
    <h3 class="mb-4">💳 تفاصيل الدفع</h3>

    <ul class="list-group mb-4">
        <li class="list-group-item"><strong>اسم الطالب:</strong> {{ $student->student_name_ar }}</li>
        <li class="list-group-item"><strong>رقم الفاتورة:</strong> {{ $invoice->invoice_number }}</li>
        <li class="list-group-item"><strong>المبلغ المدفوع:</strong> {{ number_format($payment->amount, 2) }} ريال</li>
        <li class="list-group-item"><strong>تاريخ الدفع:</strong> {{ $payment->payment_date }}</li>
        <li class="list-group-item"><strong>طريقة الدفع:</strong> {{ $source->name ?? 'غير محددة' }}</li>
        <li class="list-group-item">
            <strong>الحالة:</strong>
            <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">
                {{ $payment->status === 'completed' ? 'مكتمل' : 'قيد الانتظار' }}
            </span>
        </li>
    </ul>

    <a href="{{ route('admin.payments.index') }}" class="btn btn-primary">🔙 العودة لقائمة الدفعات</a>
</div>
@endsection
