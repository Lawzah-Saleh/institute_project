@extends('admin.layouts.app')

@section('title', 'إضافة دفع للطالب')

@section('content')
<div class="page-wrapper" style="margin-right: 100px;padding:50px 20px;width: calc(105% - 150px);background:#f7f7fa;">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">إضافة دفع للطالب</h3>
                </div>
            </div>
        </div>

        <!-- عرض تفاصيل الطالب بعد البحث -->
        <div class="card mb-4">
            <div class="card-body">
                <h5>معلومات الطالب</h5>
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

        <!-- عرض الفواتير الخاصة بالطالب -->
        <form action="{{ route('admin.payments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">

            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">الفواتير الخاصة بالطالب</h5>
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
