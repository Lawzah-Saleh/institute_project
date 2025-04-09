<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>التقرير المالي</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>التقرير المالي للطلاب المدفوعين</h1>
    <table>
        <thead>
            <tr>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>المبلغ المدفوع</th>
                <th>المبلغ المستحق</th>
                <th>المبلغ المتبقي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->student_name_ar }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ number_format($student->payments_sum_total_amount, 2) }} ريال</td>
                    <td>{{ number_format($student->invoices_sum_amount, 2) }} ريال</td>
                    <td>{{ number_format($student->invoices_sum_amount - $student->payments_sum_total_amount, 2) }} ريال</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
