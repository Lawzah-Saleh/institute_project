@extends('admin.layouts.app')

@section('title', 'تقرير المدفوعات من تاريخ إلى تاريخ')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <!-- العنوان -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تقرير المدفوعات من تاريخ إلى تاريخ</h3>
                </div>
            </div>
        </div>

        <!-- فلاتر البحث -->
        <form method="GET" action="{{ route('admin.reports.payment_statement_report') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>تاريخ البداية:</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label>تاريخ النهاية:</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4 mt-3">
                    <button type="submit" class="btn "style="background-color: #196098;color: white;">بحث</button>
                </div>
            </div>
        </form>

        <!-- جدول التقرير -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">تفاصيل المدفوعات بين {{ $startDate->format('Y-m-d') }} و {{ $endDate->format('Y-m-d') }}</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>رقم الفاتورة</th>
                            <th>اسم الطالب</th>
                            <th>المبلغ المدفوع</th>
                            <th>تاريخ الدفع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->invoice_number }}</td>
                                <td>{{ $payment->student->student_name_ar }} ({{ $payment->student->student_name_en }})</td>
                                <td>{{ number_format($payment->total_amount, 2) }} ريال</td>
                                <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">لا توجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- زر التصدير -->
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{ route('admin.reports.export_excel_payment_statement', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn "style="background-color: #e94c21;color: white">
                    تصدير إلى Excel
                </a>
                <a href="{{ route('admin.reports.export_pdf_payment_statement', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn "style="background-color: #e94c21;color: white">
                    تصدير إلى PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
