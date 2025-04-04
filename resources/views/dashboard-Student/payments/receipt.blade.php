


@extends('dashboard-Student.layouts.app')

@section('title', 'تأكيد السداد')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                <h3 class="page-title" style="color: #196098; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                    <i class="fas fa-receipt" style="margin-left: 15px; color: #196098; font-size: 1.2rem;"></i>
                    تأكيد السداد
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card text-center" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl;">
            <h5 class="text-success">✅ تم تسجيل الدفع بنجاح</h5>
            <p>رقم الحافظة للسداد عبر البريد:</p>
            <h4 class="text-primary" id="receiptNumber">820525</h4>
            <button class="btn custom-btn mt-2" onclick="copyReceipt()">📋 نسخ الرقم</button>
        </div>
    </div>
</div>

<script>
    function copyReceipt() {
        const receipt = document.getElementById('receiptNumber').innerText;
        navigator.clipboard.writeText(receipt).then(() => {
            const button = event.target;
            const original = button.innerHTML;
            button.innerHTML = "✅ تم النسخ!";
            button.disabled = true;
            setTimeout(() => {
                button.innerHTML = original;
                button.disabled = false;
            }, 2000);
        });
    }
</script>

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
        padding: 10px 20px;
        font-weight: bold;
        transition: all 0.3s ease-in-out;
        position: relative;
        overflow: hidden;
        z-index: 1;
        border: none;
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
