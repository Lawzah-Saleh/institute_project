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
        <p><label>رقم الفاتورة:</label> {{ $invoice->invoice_number }}</p>
        <p><label>اسم الطالب:</label> {{ $student->student_name_ar }}</p>
        <p><label>تاريخ الفاتورة:</label> {{ $invoice->created_at->format('Y-m-d H:i') }}</p>
        <p><label>المبلغ المدفوع:</label> {{ number_format($invoice->amount, 2) }} ريال</p>
        <p><label>تفاصيل:</label> {{ $invoice->invoice_details }}</p>
        <p><label>طريقة الدفع:</label> {{ $invoice->paymentSource->name ?? 'غير محدد' }}</p>
    </div>



    <div class="print-btn">
        <button onclick="window.print()">🖨️ طباعة السند</button>
    </div>

    <div class="footer">
        معهد التعليم أولاً - جميع الحقوق محفوظة © {{ date('Y') }}
    </div>
</div>

</body>
</html>
