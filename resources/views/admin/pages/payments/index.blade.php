@extends('admin.layouts.app')

@section('title', 'جميع المدفوعات')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">قائمة جميع المدفوعات</h3>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الطالب</th>
                        <th>رقم الفاتورة</th>
                        <th>المبلغ المدفوع</th>
                        <th>طريقة الدفع</th>
                        <th>تاريخ الدفع</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $payment->student->student_name_ar ?? '-' }}</td>
                        <td>{{ $payment->invoice->invoice_number ?? '-' }}</td>
                        <td>{{ number_format($payment->amount, 2) }} ريال</td>
                        <td>{{ $payment->source->name ?? 'غير محددة' }}</td>
                        <td>{{ $payment->payment_date }}</td>
                        <td>
                            <span class="badge {{ $payment->status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                                {{ $payment->status == 'completed' ? 'مكتمل' : 'قيد الانتظار' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
