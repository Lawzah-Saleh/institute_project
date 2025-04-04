

 @extends('dashboard-Student.layouts.app')

@section('title', 'المدفوعات المسددة')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                <h3 class="page-title" style="color: #196098; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                    <i class="fas fa-check-circle" style="margin-left: 15px; color: #196098; font-size: 1.2rem;"></i>
                    المدفوعات المسددة
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
            <div class="card-body">
                @if ($paidInvoices->isEmpty())
                    <div class="alert alert-warning" role="alert" style="text-align: center;">
                        <strong>لا توجد مدفوعات مسددة حالياً.</strong>
                    </div>
                @else
                    @foreach ($paidInvoices as $invoice)
                        <div class="mb-3">
                            <p><strong>رقم الفاتورة:</strong> #{{ $invoice->id }}</p>
                            <p><strong>تاريخ الدفع:</strong> {{ $invoice->paid_at }}</p>
                            <p><strong>المبلغ:</strong> {{ number_format($invoice->amount, 2) }}$</p>
                        </div>
                        <hr>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #f0f8ff, #e6e6fa);
        margin: 0;
        padding: 0;
        font-family: 'Roboto', sans-serif;
        direction: rtl;
    }

    .page-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #196098;
    }

    .card {
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin: 10px 0;
    }
</style>
@endsection
