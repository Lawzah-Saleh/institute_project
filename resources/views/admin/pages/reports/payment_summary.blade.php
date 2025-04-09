@extends('admin.layouts.app')

@section('title', 'تقرير المبالغ المسددة')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <!-- العنوان -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تقرير المبالغ المسددة</h3>
                </div>
            </div>
        </div>

        <!-- فلاتر البحث -->
        <form method="GET" action="{{ route('admin.reports.payment_summary') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>اختر الفترة:</label>
                    <select name="period" class="form-control">
                        <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>اليوم</option>
                        <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>الشهر</option>
                        <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>السنة</option>
                    </select>
                </div>
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </div>
        </form>

        <!-- جدول التقرير -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">ملخص المدفوعات</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>رقم الفاتورة</th>
                            <th>اسم الطالب</th>
                            <th>المبلغ المدفوع</th>
                            <th>المبلغ الكلي</th>
                            <th>تاريخ الدفع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->invoice_number }}</td>
                                <td>{{ $payment->student->student_name_ar }} ({{ $payment->student->student_name_en }})</td>
                                <td>{{ number_format($payment->amount_paid, 2) }} ريال</td>
                                <td>{{ number_format($payment->total_amount, 2) }} ريال</td>
                                <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">لا توجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- زر التصدير -->
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{ route('admin.reports.export_excel_payment_summary', ['period' => request('period')]) }}" class="btn btn-success">
                    تصدير إلى Excel
                </a>
                <a href="{{ route('admin.reports.export_pdf_payment_summary', ['period' => request('period')]) }}" class="btn btn-danger">
                    تصدير إلى PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
