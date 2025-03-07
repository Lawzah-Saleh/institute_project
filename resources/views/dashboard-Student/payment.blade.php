

@extends('dashboard-Student.layouts.app')

@section('title', 'تفاصيل دفع الطالب')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                <h3 class="page-title" style="color: #196098; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                    <i class="fas fa-money-bill" style="margin-left: 15px; color: #196098; font-size: 1.2rem;"></i>
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
                <p><strong>اسم الطالب:</strong> أحمد علي</p>
                <p><strong>رقم الطالب:</strong> 56789</p>
                <p><strong>رقم الحافظة:</strong> 12345</p>
                <p><strong>التاريخ:</strong> 2024-12-28</p>
                <p><strong>المبلغ:</strong> $200</p>
                <p><strong>تفاصيل الحافظة:</strong> دفع رسوم الدراسة لشهر ديسمبر 2024</p>

                <h4 style="margin-top: 20px; color: #196098;">تفاصيل الدفعة</h4>
                <table class="table table-bordered" style="margin-top: 20px; direction: rtl; text-align: right;">
                    <thead>
                        <tr>
                            <th style="background-color: #196098; color: white; text-align: center;">#</th>
                            <th style="background-color: #196098; color: white; text-align: center;">البند</th>
                            <th style="background-color: #196098;color: white; text-align: center;">الوصف</th>
                            <th style="background-color: #196098; color: white; text-align: center;">المبلغ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center;">1</td>
                            <td>رسوم الدراسة</td>
                            <td>رسوم الدراسة الشهرية</td>
                            <td style="text-align: center;">$150</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">2</td>
                            <td>النقل</td>
                            <td>رسوم الحافلة الشهرية</td>
                            <td style="text-align: center;">$50</td>
                        </tr>
                    </tbody>
                </table>

                <h4 style="margin-top: 20px; color: #196098;">تفاصيل السداد</h4>
                <div class="table-responsive">
                    <table class="table table-bordered" style="margin-top: 20px; direction: rtl; text-align: right;">
                        <thead>
                            <tr>
                                <th style="background-color: #196098; color: white; text-align: center;">رقم السداد</th>
                                <th style="background-color: #196098; color: white; text-align: center;">جهة السداد</th>
                                <th style="background-color: #196098;color: white; text-align: center;">تاريخ السداد</th>
                                <th style="background-color: #196098; color: white; text-align: center;">رقم الطالب</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center;">00123</td>
                                <td>البنك الأهلي</td>
                                <td>2024-12-28</td>
                                <td style="text-align: center;">56789</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #f0f8ff, #e6e6fa);
        margin: 0;
        padding: 0;
        font-family: 'Roboto', sans-serif;
        direction: rtl;
    }

    .card {
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        color: #333;
        margin: 10px 0;
    }

    .table th {
        background-color: #196098;
        color: white;
        text-align: center;
    }

    .table td {
        text-align: center;
    }

    .page-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #196098;
    }
</style>
@endsection


{{-- ////////////////////////// --}}

{{-- @extends('dashboard-Student.layouts.app')

@section('title', 'تفاصيل دفع الطالب')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                <h3 class="page-title" style="color: #e94c21; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                    <i class="fas fa-money-bill" style="margin-left: 15px; color: #e94c21; font-size: 1.2rem;"></i>
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
                <p><strong>اسم الطالب:</strong> أحمد علي</p>
                <p><strong>رقم الطالب:</strong> 56789</p>
                <p><strong>رقم الحافظة:</strong> 12345</p>
                <p><strong>التاريخ:</strong> 2024-12-28</p>
                <p><strong>المبلغ:</strong> $200</p>
                <p><strong>تفاصيل الحافظة:</strong> دفع رسوم الدراسة لشهر ديسمبر 2024</p>

                <h4 style="margin-top: 20px; color: #e94c21;">تفاصيل الدفعة</h4>
                <table class="table table-bordered" style="margin-top: 20px; direction: rtl; text-align: right;">
                    <thead>
                        <tr>
                            <th style="background-color: #e94c21; color: white; text-align: center;">#</th>
                            <th style="background-color: #e94c21; color: white; text-align: center;">البند</th>
                            <th style="background-color: #e94c21; color: white; text-align: center;">الوصف</th>
                            <th style="background-color: #e94c21; color: white; text-align: center;">المبلغ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center;">1</td>
                            <td>رسوم الدراسة</td>
                            <td>رسوم الدراسة الشهرية</td>
                            <td style="text-align: center;">$150</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">2</td>
                            <td>النقل</td>
                            <td>رسوم الحافلة الشهرية</td>
                            <td style="text-align: center;">$50</td>
                        </tr>
                    </tbody>
                </table>

                <h4 style="margin-top: 20px; color: #e94c21;">تفاصيل السداد</h4>
                <div class="table-responsive">
                    <table class="table table-bordered" style="margin-top: 20px; direction: rtl; text-align: right;">
                        <thead>
                            <tr>
                                <th style="background-color: #e94c21; color: white; text-align: center;">رقم السداد</th>
                                <th style="background-color: #e94c21; color: white; text-align: center;">جهة السداد</th>
                                <th style="background-color: #e94c21; color: white; text-align: center;">تاريخ السداد</th>
                                <th style="background-color: #e94c21; color: white; text-align: center;">رقم الطالب</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center;">00123</td>
                                <td>البنك الأهلي</td>
                                <td>2024-12-28</td>
                                <td style="text-align: center;">56789</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #f0f8ff, #e6e6fa);
        margin: 0;
        padding: 0;
        font-family: 'Roboto', sans-serif;
        direction: rtl;
    }

    .card {
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        color: #333;
        margin: 10px 0;
    }

    .table th {
        background-color: #e94c21;
        color: white;
        text-align: center;
    }

    .table td {
        text-align: center;
    }

    .page-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #e94c21;
    }
</style>
@endsection --}}


