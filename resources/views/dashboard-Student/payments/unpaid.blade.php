

@extends('dashboard-Student.layouts.app')

@section('title', 'المدفوعات غير المسددة')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                <h3 class="page-title" style="color: #196098; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                    <i class="fas fa-exclamation-triangle" style="margin-left: 15px; color: #ffc107; font-size: 1.2rem;"></i>
                    المدفوعات غير المسددة
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
            <div class="card-body">
                @if($unpaidInvoices->isEmpty())
                    <div class="alert alert-success" role="alert" style="text-align: center;">
                        <strong>تم تسديد جميع المدفوعات!</strong>
                    </div>
                @else
                    @foreach($unpaidInvoices as $invoice)
                        <div class="mb-3">
                            <p><strong>رقم الفاتورة:</strong> #{{ $invoice->id }}</p>
                            <p><strong>المبلغ:</strong> ${{ $invoice->amount }}</p>
                            <p><strong>تاريخ الاستحقاق:</strong> {{ $invoice->due_date }}</p>
                            <a href="{{ route('student.payment.pay', $invoice->id) }}" class="btn custom-btn w-100 mt-2">ادفع الآن</a>
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

    .custom-btn {
        background-color: #196098 !important;
        color: white !important;
        border-radius: 8px;
        padding: 10px;
        font-weight: bold;
        transition: all 0.3s ease-in-out;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .custom-btn::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-color: rgba(255, 255, 255, 0.15); /* تأثير الشفافية */
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
        z-index: 0;
        pointer-events: none;
    }

    .custom-btn:hover::after,
    .custom-btn:active::after {
        opacity: 1;
    }
</style>
@endsection
{
