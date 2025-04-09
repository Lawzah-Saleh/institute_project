@extends('admin.layouts.app')

@section('title', 'تقرير ميزانية الدفع')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <!-- العنوان -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تقرير ميزانية الدفع</h3>
                </div>
            </div>
        </div>

        <!-- فلاتر البحث -->
        <form method="GET" action="{{ route('admin.reports.payment_budget_report') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>اختر الفترة:</label>
                    <select name="period" class="form-control">
                        <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>اليوم</option>
                        <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>الشهر</option>
                        <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>السنة</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>اختر القسم:</label>
                    <select name="department_id" id="department_id" class="form-control">
                        <option value="">-- اختر القسم --</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mt-3">
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
                            <th>رقم الطالب</th>
                            <th>الاسم</th>
                            <th>المبلغ المدفوع</th>
                            <th>إجمالي المبلغ</th>
                            <th>المتبقي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->student->id }}</td>
                                <td>{{ $payment->student->student_name_ar }} ({{ $payment->student->student_name_en }})</td>
                                <td>{{ number_format($payment->total_amount, 2) }} ريال</td>
                                <td>{{ number_format($payment->student->invoices->sum('amount'), 2) }} ريال</td>
                                <td>{{ number_format($payment->student->invoices->sum('amount') - $payment->total_amount, 2) }} ريال</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- المبالغ الإجمالية -->
        <div class="card">
            <div class="card-body">
                <h5>إجمالي المدفوعات: {{ number_format($totalPaid, 2) }} ريال</h5>
                <h5>إجمالي المبلغ المستحق: {{ number_format($totalDue, 2) }} ريال</h5>
                <h5>المبلغ المتبقي: {{ number_format($remainingAmount, 2) }} ريال</h5>
            </div>
        </div>

        <!-- زر التصدير -->
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{ route('admin.reports.export_excel_payment_budget', ['period' => request('period'), 'department_id' => request('department_id')]) }}" class="btn btn-success">
                    تصدير إلى Excel
                </a>
                <a href="{{ route('admin.reports.export_pdf_payment_budget', ['period' => request('period'), 'department_id' => request('department_id')]) }}" class="btn btn-danger">
                    تصدير إلى PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
