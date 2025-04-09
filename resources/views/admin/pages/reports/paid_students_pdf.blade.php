<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h2>تقرير الطلاب المدفوعين</h2>
    <table>
        <thead>
            <tr>
                <th>اسم الطالب</th>
                <th>البريد الإلكتروني</th>
                <th>المبلغ المدفوع</th>
                <th>المتبقي</th>
                <th>رقم الفاتورة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                @foreach($student->payments as $payment)
                    <tr>
                        <td>{{ $student->student_name_ar }} ({{ $student->student_name_en }})</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ number_format($payment->total_amount, 2) }}</td>
                        <td>{{ number_format($payment->remaining_amount, 2) }}</td>
                        <td>{{ $payment->invoice_number }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
