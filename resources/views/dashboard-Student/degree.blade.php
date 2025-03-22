@extends('dashboard-Student.layouts.app')

@section('title', 'الدرجات')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header"
                 style="background: white; padding: 15px; border-radius: 8px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                <h3 class="page-title"
                    style="color: #196098; display: flex; align-items: center; font-family: 'Roboto', sans-serif;">
                    <i class="fas fa-clipboard-list"
                       style="margin-left: 15px; color: #196098; font-size: 1.2rem;"></i>
                    تفاصيل درجات الطالب
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card"
             style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl;">
            <div class="card-body">
                <h4 style="color: #333;">معلومات الطالب</h4>
                <p><strong>رقم الطالب :</strong> {{ $student->id }}</p>
                <p><strong>الاسم:</strong> {{ $student->student_name_ar }}</p>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered mt-4 text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>الدورة</th>
                                <th>العملي</th>
                                <th>النهائي</th>
                                <th>الحضور</th>
                                <th>المجموع</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($degrees as $index => $degree)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $degree->course_name }}</td>
                                    <td>{{ $degree->practical_degree }}</td>
                                    <td>{{ $degree->final_degree }}</td>
                                    <td>{{ $degree->attendance_degree }}</td>
                                    <td>{{ $degree->total_degree }}</td>
                                    <td>
                                        @if($degree->status == 'pass')
                                            <span class="badge badge-success">ناجح</span>
                                        @else
                                            <span class="badge badge-danger">راسب</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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
        font-family: 'Roboto', sans-serif;
        direction: rtl;
    }

    .card {
        border: 1px solid #ddd;
        margin-top: 10px;
    }

    .table th {
        background-color: #196098;
        color: white;
    }

    .badge-success {
        background-color: #1D6F42;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 0.9rem;
    }

    .badge-danger {
        background-color: #7D1A1A;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 0.9rem;
    }
</style>
@endsection
