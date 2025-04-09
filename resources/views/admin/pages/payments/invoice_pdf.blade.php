<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>فاتورة رقم: {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: center; }
    </style>
</head>
<body>
    <h3>تفاصيل الفاتورة: {{ $invoice->invoice_number }}</h3>
    <table>
        <tr>
            <th>رقم الفاتورة</th>
            <th>المبلغ المدفوع</th>
            <th>تاريخ الدفع</th>
        </tr>
        <tr>
            <td>{{ $invoice->invoice_number }}</td>
            <td>{{ number_format($invoice->amount, 2) }} ريال</td>
            <td>{{ $invoice->paid_at->format('Y-m-d') }}</td>
        </tr>
    </table>
</body>
</html>
