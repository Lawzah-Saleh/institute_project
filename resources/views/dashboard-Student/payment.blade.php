@extends('dashboard-Student.layouts.app')

@section('title', 'تفاصيل دفع الطالب')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                <h3 class="page-title" style="color: #196098; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                    <i class="fas fa-money-bill" style="margin-left: 15px; color: #196098;"></i>
                    تفاصيل دفع الطالب
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
            <div class="card-body">
                <h4 style="color: #333;">تفاصيل الدفع</h4>
                @if($invoice)
                <p><strong>رقم الحافظة:</strong> {{ $invoice->id }}</p>
                <p><strong>التاريخ:</strong> {{ Carbon\Carbon::parse($invoice->date)->format('Y-m-d') }}</p>
                <p><strong>المبلغ:</strong> {{ number_format($invoice->amount, 2) }}</p>
                <p><strong>تفاصيل الحافظة:</strong> {{ $invoice->description }}</p>
            @else
                <p style="color: red;"><strong>لا توجد أي فواتير مسجلة لهذا الطالب.</strong></p>
            @endif


                <h4 style="margin-top: 20px; color: #196098;">تفاصيل الدفعة</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>البند</th>
                            <th>الوصف</th>
                            <th>المبلغ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($invoice)

                        @foreach ($invoice->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->description }}</td>
                                <td>${{ number_format($item->amount, 2) }}</td>
                            </tr>
                        @endforeach
                        @else
                            <p style="color: red;">لا توجد فاتورة مسجلة لهذا الطالب.</p>
                        @endif
                    </tbody>
                </table>

                <h4 style="margin-top: 20px; color: #196098;">تفاصيل السداد</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>رقم السداد</th>
                            <th>جهة السداد</th>
                            <th>تاريخ السداد</th>
                            <th>رقم الطالب</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($invoice)

                        @foreach ($invoice->payments as $payment)
                            <tr>
                                <td>{{ $payment->id }}</td>
                                <td>{{ $payment->source->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}</td>
                                <td>{{ $student->id }}</td>
                            </tr>
                        @endforeach
                        @else
    <p style="color:red;"><strong>لا توجد فواتير لهذا الطالب.</strong></p>
@endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #f0f8ff, #e6e6fa);
        font-family: 'Roboto', sans-serif;
        direction: rtl;
    }
    .card {
        border: 1px solid #ddd;
        margin: 10px 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .table th {
        background-color: #196098;
        color: white;
        text-align: center;
    }
    .table td {
        text-align: center;
    }
</style>
@endsection
