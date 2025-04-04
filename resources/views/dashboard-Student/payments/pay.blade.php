@extends('dashboard-Student.layouts.app')

@section('title', 'إدخال مبلغ السداد')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                <h3 class="page-title" style="color: #196098; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                    <i class="fas fa-money-bill-wave" style="margin-left: 15px; color: #196098; font-size: 1.2rem;"></i>
                    إدخال مبلغ السداد
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
            <div class="card-body">
                <p><strong>رقم الفاتورة:</strong> #{{ $invoice->id }}</p>
                <p><strong>المبلغ المستحق:</strong> ${{ $invoice->amount }}</p>
                <p><strong>تاريخ الاستحقاق:</strong> {{ $invoice->due_date }}</p>
                <p><strong>حالة الفاتورة:</strong> <span class="text-warning">غير مدفوعة</span></p>

                {{-- رسالة التنبيه --}}
                <div id="amount-error" class="alert alert-danger d-none" role="alert"></div>

                <form action="{{ route('student.payment.receipt') }}" method="GET" onsubmit="return validateAmount()">
                    <div class="form-group mt-3">
                        <label for="amount">أدخل مبلغ السداد</label>
                        <input type="number" id="amount" name="amount" class="form-control" placeholder="أدخل مبلغ السداد">
                        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                    </div>
                    <button type="submit" class="btn custom-btn w-100 mt-3">إرسال</button>
                </form>
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

<script>
    function validateAmount() {
        const amountInput = document.getElementById('amount');
        const errorDiv = document.getElementById('amount-error');
        const amount = parseFloat(amountInput.value);

        if (isNaN(amount) || amount <= 0) {
            showError("يرجى إدخال مبلغ صحيح أكبر من صفر");
            amountInput.focus();
            return false;
        }

        return true;
    }

    function showError(message) {
        const errorDiv = document.getElementById('amount-error');
        errorDiv.innerText = message;
        errorDiv.classList.remove('d-none');

        setTimeout(() => {
            errorDiv.classList.add('d-none');
        }, 3000);
    }
</script>
@endsection
