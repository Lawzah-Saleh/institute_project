<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تأكيد تسجيل الطالب</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            direction: rtl;
            text-align: right;
            font-family: 'Cairo', sans-serif;
        }
        .card {
            border-radius: 20px;
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .card-header {
            background-color: #196098;
            color: #fff;
            font-weight: bold;
            font-size: 20px;
            border-top-right-radius: 20px;
            border-top-left-radius: 20px;
        }
        .btn-primary {
            background-color: #196098;
            border: none;
            border-radius: 30px;
            padding: 10px 30px;
        }
        .btn-primary:hover {
            background-color: #154b7a;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card text-center">
                <div class="card-header">
                    ✅ تم تسجيل الطالب بنجاح
                </div>

                <div class="card-body p-4 text-start" style="font-size: 16px;">
                    <div class="mb-3">
                        <strong class="text-secondary">👤 اسم الطالب:</strong>
                        {{ $invoice->student->student_name_ar }} ({{ $invoice->student->student_name_en }})
                    </div>

                    <div class="mb-3">
                        <strong class="text-secondary">📧 البريد الإلكتروني:</strong>
                        {{ $invoice->student->email }}
                    </div>

                    <div class="mb-3">
                        <strong class="text-secondary">📞 رقم الهاتف:</strong>
                        {{ implode(', ', json_decode($invoice->student->phones) ?? []) }}
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <strong class="text-secondary">💰 المبلغ الكلي للدفع:</strong>
                        {{ number_format($payment->total_amount, 2) }} ريال
                    </div>

                    <div class="mb-3">
                        <strong class="text-secondary">📅 تاريخ الاستحقاق:</strong>
                        {{ \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') }}
                    </div>

                    <div class="mb-3">
                        <strong class="text-secondary">🔖 رقم الحافظة:</strong>
                        <span dir="ltr" class="fw-bold text-dark">{{ $invoice->invoice_number }}</span>
                    </div>

                    <div class="mb-3">
                        <strong class="text-secondary">💵 المبلغ الذي سوف يتم تسديده:</strong>
                        {{ number_format($invoice->amount, 2) }} ريال
                    </div>

                    <div class="mb-3">
                        <strong class="text-secondary">💸 المتبقي على الطالب:</strong>
                        <span class="text-danger fw-bold">
                            {{ number_format($payment->total_amount - $invoice->amount, 2) }} ريال
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong class="text-secondary">📌 حالة الفاتورة:</strong>
                        <span class="badge badge-warning">
                            {{ $invoice->status == 0 ? 'غير مدفوع' : 'مدفوع' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong class="text-secondary">📅 تاريخ الدفع:</strong>
                        @if($invoice->paid_at)
                            {{ \Carbon\Carbon::parse($invoice->paid_at)->format('Y-m-d H:i:s') }}
                        @else
                            <span class="text-danger">لم يتم الدفع بعد</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong class="text-secondary">💳 طريقة الدفع:</strong>
                        {{ $invoice->payment_sources->name ?? 'عبر البريد' }}
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

</body>
</html>
