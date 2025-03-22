@extends('dashboard-Student.layouts.app')

@section('title', 'الدرجات')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                <h3 class="page-title" style="color: #196098; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                    <i class="fas fa-clipboard-list" style="margin-left: 15px; color: #196098; font-size: 1.2rem;"></i>
                    تفاصيل درجات الطالب
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
            <div class="card-body">
                <h4 style="color: #333;">تفاصيل نتائج الطالب</h4>
                <p><strong>اسم الطالب:</strong> {{ $student->student_name_ar }}</p>
                <p><strong>رقم التعريف:</strong> {{ $student->id }}</p>
                
                {{-- <p><strong>الدورة:</strong>{{$degrees->course_name}}</p> --}}
                <table class="table table-bordered" style="margin-top: 20px; direction: rtl; text-align: right;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الدورة</th>
                            <th>العلامات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($degrees as $index => $degree)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $degree->course_name }}</td>
                                <td>
                                    <strong>العملي:</strong> {{ $degree->practical_degree }} /
                                    <strong>النهائي:</strong> {{ $degree->final_degree }} /
                                    <strong>الحضور:</strong> {{ $degree->attendance_degree }} /
                                    <strong>المجموع:</strong> {{ $degree->total_degree }} /
                                    <strong>الحالة:</strong> {{ $degree->status == 'pass' ? 'ناجح' : 'راسب' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
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
