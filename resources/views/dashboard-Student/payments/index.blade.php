@extends('dashboard-Student.layouts.app')
@section('title', 'المدفوعات')
@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                <h3 class="page-title" style="color: #196098; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                    <i class="fas fa-credit-card" style="margin-left: 15px; color: #196098; font-size: 1.2rem;"></i>
                    واجهة المدفوعات
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
            <div class="card-body text-center">
                <h4 style="color: #333;">اختر نوع الدفعة</h4>
                <div class="row mt-4">
                    <div class="col-md-6 mb-2">
                        <a href="{{ route('student.payment.paid') }}"
                           class="btn w-100 py-3 payment-btn"
                           style="background: linear-gradient(to right, #196098, #3382b8);">
                            💳 المدفوعات
                        </a>
                    </div>
                    <div class="col-md-6 mb-2">
                        <a href="{{ route('student.payment.unpaid') }}"
                           class="btn w-100 py-3 payment-btn"
                           style="background: linear-gradient(to right, #196098, #3382b8);">
                            ⚠️ المدفوعات غير المسددة
                        </a>
                    </div>


                </div>
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

    .payment-btn {
        color: white !important; /* ثبات لون النص */
        border-radius: 8px;
        transition: all 0.3s ease-in-out;
        font-weight: bold;
        font-size: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .payment-btn::after {
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

    .payment-btn:hover::after,
    .payment-btn:active::after {
        opacity: 1;
    }

    /* تأكيد إضافي أن النص ما يتأثر بأي كلاس bootstrap */
    .payment-btn:hover,
    .payment-btn:active,
    .payment-btn:focus {
        color: white !important;
        text-decoration: none;
    }
</style>
@endsection
