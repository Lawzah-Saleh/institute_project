@extends('admin.layouts.app')

@section('title', 'تفاصيل مدفوعات الطالب')

@section('content')
<div class="page-wrapper" style="margin-right: 100px;padding:50px 20px;width: calc(105% - 150px);background:#f7f7fa;">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تفاصيل مدفوعات الطالب</h3>
                </div>
            </div>
        </div>

        <!-- معلومات الطالب -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">🧑‍🎓 معلومات الطالب:</h5>
                <p><strong>الاسم:</strong> {{ $student->student_name_ar }} ({{ $student->student_name_en }})</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $student->email }}</p>
                <p><strong>رقم الهاتف:</strong> {{ json_decode($student->phones, true) ? implode(', ', json_decode($student->phones, true)) : 'غير متوفر' }}</p>
            </div>
        </div>

        <!-- الفواتير والمدفوعات -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">💰 الفواتير والمدفوعات</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>رقم الحافظة</th>
                            <th>تاريخ الاستحقاق</th>
                            <th>المبلغ الكلي</th>
                            <th>المبلغ المدفوع</th>
                            <th>المتبقي</th>
                            <th>الحالة</th>
                            <th>الإجراء</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->invoices as $invoice)
                            @php
                                $paid = $invoice->payments->sum('amount');
                                $remaining = $invoice->amount - $paid;
                            @endphp
                            <tr>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->due_date }}</td>
                                <td>{{ number_format($invoice->amount, 2) }} ريال</td>
                                <td>{{ number_format($paid, 2) }} ريال</td>
                                <td>{{ number_format($remaining, 2) }} ريال</td>
                                <td>
                                    @if($remaining <= 0)
                                        <span class="badge bg-success">مدفوع</span>
                                    @elseif($paid > 0)
                                        <span class="badge bg-warning text-dark">مدفوع جزئياً</span>
                                    @else
                                        <span class="badge bg-danger">غير مدفوع</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.payments.invoice.show', $invoice->id) }}" class="btn btn-sm btn-info">عرض الفاتورة</a>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">لا توجد فواتير مسجلة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
