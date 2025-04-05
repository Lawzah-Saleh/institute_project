@extends('admin.layouts.app')

@section('title', 'تفاصيل الفاتورة')

@section('content')
<div class="page-wrapper" style="margin-right: 100px;padding:50px 20px;width: calc(105% - 150px);background:#f7f7fa;">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تفاصيل الفاتورة</h3>
                </div>
            </div>
        </div>

        <!-- معلومات الفاتورة -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">📑 تفاصيل الفاتورة:</h5>
                <p><strong>رقم الفاتورة:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>تاريخ الاستحقاق:</strong> {{ $invoice->due_date }}</p>
                <p><strong>المبلغ الكلي:</strong> {{ number_format($invoice->amount, 2) }} ريال</p>
            </div>
        </div>

        <!-- المدفوعات المرتبطة بالفاتورة -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">💰 المدفوعات المرتبطة بالفاتورة</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>تاريخ الدفع</th>
                            <th>المبلغ المدفوع</th>
                            <th>طريقة الدفع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date }}</td>
                                <td>{{ number_format($payment->amount, 2) }} ريال</td>
                                <td>{{ $payment->payment_method }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- المبلغ المتبقي -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">💸 المبلغ المتبقي:</h5>
                <p><strong>المتبقي:</strong> {{ number_format($invoice->amount - $invoice->payments->sum('amount'), 2) }} ريال</p>
            </div>
        </div>
    </div>
</div>
@endsection
