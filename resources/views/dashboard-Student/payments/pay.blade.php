@extends('dashboard-Student.layouts.app')

@section('title', 'دفع المدفوعات')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                <h3 class="page-title" style="color: #196098; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                    <i class="fas fa-credit-card" style="margin-left: 15px; color: #28a745; font-size: 1.2rem;"></i>
                    دفع المدفوعات
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
            <div class="card-body">
                <h4>تفاصيل الدفع</h4>

                @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('student.payment.process', ['paymentId' => $payment->id]) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="amount_paid">المبلغ المدفوع</label>
                        <input type="number" class="form-control" name="amount_paid" id="amount_paid" value="{{ old('amount_paid') }}" placeholder="أدخل المبلغ الذي ترغب في دفعه" required>
                    </div>

                    <div class="form-group">
                        <label for="payment_sources_id">طريقة الدفع</label>
                        <select name="payment_sources_id" id="payment_sources_id" class="form-control" required>
                            <option value="">-- اختر طريقة الدفع --</option>
                            @foreach($paymentSources as $paymentSource)
                                <option value="{{ $paymentSource->id }}" {{ old('payment_sources_id') == $paymentSource->id ? 'selected' : '' }}>
                                    {{ $paymentSource->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn custom-btn w-100 mt-3">دفع الآن</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
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
        background-color: rgba(255, 255, 255, 0.15);
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
