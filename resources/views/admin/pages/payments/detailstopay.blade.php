@extends('admin.layouts.app')

@section('title', 'تفاصيل الدفع للطالب')

@section('content')
<div class="page-wrapper" style="margin-right: 100px;padding:50px 20px;width: calc(105% - 150px);background:#f7f7fa;">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تفاصيل الدفع للطالب</h3>
                </div>
            </div>
        </div>

        <!-- معلومات الطالب -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">معلومات الطالب</h5>
                <p><strong>الاسم:</strong> {{ $student->student_name_ar }} ({{ $student->student_name_en }})</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $student->email }}</p>
                <p><strong>رقم الهاتف:</strong>
                    @php
                        $phones = json_decode($student->phones, true);
                    @endphp
                    {{ $phones ? implode(', ', $phones) : 'غير متوفر' }}
                </p>
            </div>
        </div>

        <!-- الفواتير الخاصة بالطالب -->
        <form action="{{ route('admin.payments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-3">الفواتير الخاصة بالطالب</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>رقم الفاتورة</th>
                                <th>المبلغ الكلي</th>
                                <th>المبلغ المدفوع</th>
                                <th>المتبقي</th>
                                <th>تاريخ الاستحقاق</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student->invoices as $invoice)
                                @php
                                    $paid = $invoice->payments->sum('amount');
                                    $remaining = $invoice->amount - $paid;
                                @endphp
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ number_format($invoice->amount, 2) }} ريال</td>
                                    <td>{{ number_format($paid, 2) }} ريال</td>
                                    <td>{{ number_format($remaining, 2) }} ريال</td>
                                    <td>{{ $invoice->due_date }}</td>
                                    <td>
                                        @if($remaining <= 0)
                                            <span class="badge bg-success">مدفوع</span>
                                        @elseif($paid > 0)
                                            <span class="badge bg-warning text-dark">مدفوع جزئياً</span>
                                        @else
                                            <span class="badge bg-danger">غير مدفوع</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- إضافة الدفع للطالب -->
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">إضافة دفع جديد</h5>
                    <div class="form-group">
                        <label for="invoice_id">اختر الفاتورة</label>
                        <select name="invoice_id" id="invoice_id" class="form-control" required>
                            <option value="">اختر الفاتورة</option>
                            @foreach($student->invoices as $invoice)
                                <option value="{{ $invoice->id }}">{{ $invoice->invoice_number }} - {{ $invoice->due_date }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-3">
                        <label for="amount_paid">المبلغ المدفوع</label>
                        <input type="number" name="amount_paid" class="form-control" placeholder="أدخل المبلغ المدفوع" required>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">إضافة الدفع</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
