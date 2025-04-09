@extends('admin.layouts.app')

@section('title', 'تفاصيل الحالة المالية للطالب')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <!-- العنوان -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تفاصيل الحالة المالية للطالب</h3>
                </div>
            </div>
        </div>

        <!-- معلومات الطالب -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">معلومات الطالب:</h5>
                <p><strong>الاسم:</strong> {{ $student->student_name_ar }} ({{ $student->student_name_en }})</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $student->email }}</p>
                <p><strong>رقم الهاتف:</strong> {{ is_array($phones = json_decode($student->phones, true)) ? implode(',', $phones) : 'غير متوفر' }}</p>
            </div>
        </div>

        <!-- الحالة المالية -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">الحالة المالية:</h5>
                <p><strong>المبلغ المستحق:</strong> {{ number_format($totalAmount, 2) }} ريال</p>
                <p><strong>المبلغ المدفوع:</strong> {{ number_format($totalPaid, 2) }} ريال</p>
                <p><strong>المبلغ المتبقي:</strong> {{ number_format($remainingAmount, 2) }} ريال</p>
                <p><strong>حالة الدفع:</strong>
                    @if($remainingAmount <= 0)
                        <span class="badge bg-success">مدفوع بالكامل</span>
                    @elseif($totalPaid > 0)
                        <span class="badge bg-warning text-dark">مدفوع جزئياً</span>
                    @else
                        <span class="badge bg-danger">غير مدفوع</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{ route('admin.reports.export_excel_financial', ['search' => request('search')]) }}" class="btn btn-success">
                    تصدير إلى Excel
                </a>
                <a href="{{ route('admin.reports.export_pdf_financial', ['search' => request('search')]) }}" class="btn btn-danger">
                    تصدير إلى PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
