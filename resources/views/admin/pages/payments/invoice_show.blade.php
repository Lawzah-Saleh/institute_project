@extends('admin.layouts.app')

@section('title', 'تفاصيل المدفوعات')

@section('content')
<div class="page-wrapper" style="margin-right: 100px;padding:50px 20px;width: calc(105% - 150px);background:#f7f7fa;">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تفاصيل المدفوعات</h3>
                </div>
            </div>
        </div>

        <!-- معلومات الدفع -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">💸 تفاصيل الدفع:</h5>
                <p><strong>تاريخ الدفع:</strong> {{ $payment->payment_date }}</p>
                <p><strong>المبلغ المدفوع:</strong> {{ number_format($payment->amount, 2) }} ريال</p>
                <p><strong>طريقة الدفع:</strong> {{ $payment->payment_method }}</p>
            </div>
        </div>

        <!-- الفواتير المرتبطة بهذه الدفعة -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">📑 الفواتير المرتبطة بهذه الدفعة</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>رقم الفاتورة</th>
                            <th>تاريخ الاستحقاق</th>
                            <th>المبلغ</th>
                            <th>المتبقي</th>
                            <th>حالة الفاتورة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payment->invoices as $invoice)
                            @php
                                $paidAmount = $invoice->payments->sum('amount'); // Total paid for this invoice
                                $remainingAmount = $invoice->amount - $paidAmount; // Remaining amount
                            @endphp
                            <tr>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->due_date }}</td>
                                <td>{{ number_format($invoice->amount, 2) }} ريال</td>
                                <td>{{ number_format($remainingAmount, 2) }} ريال</td>
                                <td>
                                    @if($remainingAmount <= 0)
                                        <span class="badge bg-success">مدفوع بالكامل</span>
                                    @elseif($paidAmount > 0)
                                        <span class="badge bg-warning text-dark">مدفوع جزئياً</span>
                                    @else
                                        <span class="badge bg-danger">غير مدفوع</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
