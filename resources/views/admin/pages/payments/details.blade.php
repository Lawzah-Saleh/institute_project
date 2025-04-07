@extends('admin.layouts.app')

@section('title', 'تفاصيل مدفوعات الطالب')

@section('content')
<div class="page-wrapper" style="margin-right: 100px;padding:50px 20px;width: calc(105% - 150px);background:#f7f7fa;">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تفاصيل مدفوعات الطالب</h3>
                </div>
            </div>
        </div>

        <!-- معلومات الطالب -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3"> معلومات الطالب:</h5>
                <p><strong>الاسم:</strong> {{ $student->student_name_ar }} ({{ $student->student_name_en }})</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $student->email }}</p>
                <p><strong>رقم الهاتف:</strong> {{ is_array($phones = json_decode($student->phones, true)) ? implode(',', $phones) : 'غير متوفر' }}</p>
            </div>
        </div>

        <!-- المبلغ الإجمالي  -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3"> المبلغ الإجمالي على الطالب :</h5>
                <p><strong>المبلغ :</strong> {{ number_format($totalPayments, 2) }} ريال</p>
                <p><strong>حالة الدفع:</strong>
                    @if($remainingAmount <= 0)
                        <span class="badge bg-success">مدفوع بالكامل</span>
                    @elseif($totalPayments > 0)
                        <span class="badge bg-warning text-dark">مدفوع جزئياً</span>
                    @else
                        <span class="badge bg-danger">غير مدفوع</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- الفواتير والمدفوعات -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3"> الفواتير </h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>رقم الفاتورة</th>
                            <th>رقم الحافظة</th>
                            <th>تاريخ الاستحقاق</th>
                            <th>المبلغ المستحق</th>
                            <th>المتبقي</th>
                            <th>حالة الفاتورة</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->invoices as $invoice)

                            <tr>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->invoice_number }}</td> <!-- Assuming invoice number is the same as the invoice number -->
                                <td>{{ $invoice->created_at }}</td>
                                <td>{{ number_format($invoice->amount, 2) }} ريال</td>
                                <td>{{ number_format($remainingAmount, 2) }} ريال</td>
                                <td>
                                    @if($invoice->status == 0)
                                        <span class="badge "style="background-color: #e94c21; color: #fff;">غير مدفوع</span>
                                    @elseif($invoice->status == 1)
                                        <span class="badge "style="background-color: #e94c21; color: #fff;">مدفوع</span>
                                    @else
                                        <span class="badge"style="background-color: #e94c21; color: #fff;">لا يوجد حالة</span>
                                    @endif
                                </td>
                                 <td>
                                    <a href="{{ route('admin.payments.invoice.show', $invoice->id) }}" class="btn btn-sm "style="background-color: #196098; color: #fff;">عرض الفاتورة</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">لا توجد فواتير مسجلة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
