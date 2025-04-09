@extends('admin.layouts.app')

@section('title', 'تفاصيل مدفوعات الطالب')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <!-- العنوان -->
        <div class="page-header mb-4">
            <h3 class="page-title">تفاصيل مدفوعات الطالب</h3>
        </div>

        <!-- اختيار الدورة -->
        <form method="GET" action="{{ route('admin.student.payment.details', $student->id) }}" class="mb-4">
            <div class="form-group">
                <label for="course_id">اختر الدورة:</label>
                <select name="course_id" id="course_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- اختر الدورة --</option>
                    @foreach($student->payments as $p)
                        <option value="{{ $p->course_id }}" {{ request('course_id') == $p->course_id ? 'selected' : '' }}>
                            {{ $p->course?->course_name ?? 'دورة غير محددة' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <!-- معلومات الطالب -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">معلومات الطالب:</h5>
                <p><strong>الاسم:</strong> {{ $student->student_name_ar }} ({{ $student->student_name_en }})</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $student->email }}</p>
                <p><strong>رقم الهاتف:</strong> {{ is_array($phones = json_decode($student->phones, true)) ? implode(',', $phones) : 'غير متوفر' }}</p>
            </div>
        </div>

        <!-- المبلغ الإجمالي للدورة المختارة -->
        @if($payment)
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">
                    المبلغ الإجمالي لرسوم الدورة:
                    <span class="text-primary">{{ $payment->course?->course_name }}</span>
                </h5>
                <p><strong>المبلغ المطلوب:</strong> {{ number_format($totalAmount, 2) }} ريال</p>
                <p><strong>المبلغ المدفوع:</strong> {{ number_format($totalPayments, 2) }} ريال</p>
                <p><strong>المتبقي:</strong> {{ number_format($remainingAmount, 2) }} ريال</p>
                <p><strong>حالة الدفع:</strong>
                    @if($remainingAmount <= 0)
                        <span class="badge bg-success">مدفوع بالكامل</span>
                    @elseif($totalPayments > 0)
                        <span class="badge bg-warning text-dark">مدفوع جزئياً</span>
                    @else
                        <span class="badge bg-danger">غير مدفوع</span>
                    @endif
                </p>
                <a href="{{ route('payments.edit', $payment->id) }}" class="btn"style="background-color: #196098; color: #fff;">تعديل رسوم الدورة</a>
            </div>
        </div>
        @endif

        <!-- جدول الفواتير -->
        @if($payment && $payment->invoices->count())
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">سجل الفواتير</h5>
                <table class="table table-bordered text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>رقم الفاتورة</th>
                            <th>رقم الحافظة</th>
                            <th>تاريخ الاستحقاق</th>
                            <th>المبلغ المستحق</th>
                            <th>المتبقي</th>
                            <th>حالة الفاتورة</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payment->invoices as $invoice)
                            @php
                                $paid = $invoice->status ? $invoice->amount : 0;
                                $remaining = $invoice->amount - $paid;
                            @endphp
                            <tr>
                                <td>{{ $invoice->id }}</td>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->due_date ?? $invoice->created_at->format('Y-m-d') }}</td>
                                <td>{{ number_format($invoice->amount, 2) }} ريال</td>
                                <td>{{ number_format($remainingAmount, 2) }} ريال</td>
                                <td>
                                    @if($invoice->status == 0)
                                        <span class="badge bg-danger">غير مدفوع</span>
                                    @elseif($invoice->status == 1)
                                        <span class="badge bg-success">مدفوع</span>
                                    @else
                                        <span class="badge bg-secondary">غير معروف</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-sm "style="background-color: #e94c21; color: #fff;">عرض</a>
                                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-sm "style="background-color: #e94c21; color: #fff;">تعديل</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @elseif($payment)
        <div class="alert alert-info mt-4 text-center">لا توجد فواتير مرتبطة بهذه الدورة.</div>
        @endif

    </div>
</div>
@endsection
