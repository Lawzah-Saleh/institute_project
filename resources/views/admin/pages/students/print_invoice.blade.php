<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>سند دفع - {{ $student->student_name_ar }}</title>
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            direction: rtl;
            margin: 30px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #eee;
            padding: 30px;
            background-color: #fff;
        }
        h2 {
            text-align: center;
            color: #1b4b72;
        }
        .info {
            margin-bottom: 20px;
        }
        .info label {
            font-weight: bold;
        }
        .details, .payment {
            width: 100%;
            border-collapse: collapse;
        }
        .details td, .payment td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: gray;
        }
        .print-btn {
            margin: 20px auto;
            text-align: center;
        }
        .print-btn button {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .print-btn button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <h2>سند دفع رسمي</h2>

    <div class="info">
        <p><label>اسم الطالب:</label> {{ $student->student_name_ar }}</p>
        <p><label>رقم الفاتورة:</label> {{ $invoice->invoice_number }}</p>
        <p><label>تاريخ الفاتورة:</label> {{ $invoice->created_at->format('Y-m-d H:i') }}</p>
        <p><label>المبلغ الإجمالي:</label> {{ number_format($invoice->amount, 2) }} ريال</p>
        <p><label>تفاصيل:</label> {{ $invoice->invoice_details }}</p>
    </div>

    @if ($payment)
        <table class="payment">
            <tr>
                <td><strong>تاريخ الدفع:</strong></td>
                <td>{{ $payment->payment_date->format('Y-m-d H:i') }}</td>
            </tr>
            <tr>
                <td><strong>المبلغ المدفوع:</strong></td>
                <td>{{ number_format($payment->amount, 2) }} ريال</td>
            </tr>
            <tr>
                <td><strong>طريقة الدفع:</strong></td>
                <td>{{ $payment->source->name ?? 'غير محددة' }}</td>
            </tr>
            <tr>
                <td><strong>حالة الدفع:</strong></td>
                <td>
                    {{ $payment->status == 'completed' ? 'مدفوع' : 'غير مكتمل' }}
                </td>
            </tr>
        </table>
    @else
        <p>⚠️ لا توجد بيانات دفع مسجلة حتى الآن.</p>
    @endif

    <div class="print-btn">
        <button onclick="window.print()">🖨️ طباعة السند</button>
    </div>

    <div class="footer">
        معهد التعليم أولاً - جميع الحقوق محفوظة © {{ date('Y') }}
    </div>
</div>

</body>
</html>
