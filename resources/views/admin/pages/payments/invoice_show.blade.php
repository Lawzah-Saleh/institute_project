@extends('admin.layouts.app')

@section('title', 'تفاصيل الحافظة')

@section('content')
<div class="page-wrapper" style="margin-right: 100px;padding:50px 20px;width: calc(105% - 150px);background:#f7f7fa;">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">📄 تفاصيل الحافظة</h3>
                </div>
            </div>
        </div>

        <!-- بيانات الحافظة -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">🧾 بيانات الحافظة:</h5>
                <p><strong>رقم الحافظة:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>تفاصيل الحافظة:</strong> {{ $invoice->invoice_details }}</p>
                <p><strong>المبلغ:</strong> {{ number_format($invoice->amount, 2) }} ريال</p>
                <p><strong>تاريخ الاستحقاق:</strong> {{ $invoice->due_date }}</p>
                <p><strong>تاريخ الدفع:</strong> {{ $invoice->paid_at ?? 'لم يتم الدفع بعد' }}</p>
                <p><strong>الحالة:</strong>
                    @if($invoice->status == 1)
                        <span class="badge "style="background-color: #e94c21">مدفوعة</span>
                    @else
                        <span class="badge "style="background-color: #e94c21">غير مدفوعة</span>
                    @endif
                </p>
            </div>
            <a href="{{ route('admin.payments.downloadInvoice', $invoice->id) }}" class="btn btn-sm "style="background-color: #196098; color: #fff;">تحميل الفاتورة بصيغة PDF</a>

        </div>

    </div>
</div>
@endsection
