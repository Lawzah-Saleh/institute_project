@extends('admin.layouts.app')

@section('title', 'بيان درجات الطالب')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">بيان درجات الطالب: {{ $student->student_name_ar }}</h3>
                </div>
            </div>
        </div>

        <!-- بيانات الطالب -->
        <div class="card mb-4">
            <div class="card-body">
                <h5>معلومات الطالب:</h5>
                <p><strong>الاسم:</strong> {{ $student->student_name_ar }} ({{ $student->student_name_en }})</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $student->email }}</p>
            </div>
        </div>

        <!-- عرض الدرجات -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">الدرجات:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>اسم الدورة</th>
                            <th>درجة الحضور</th>
                            <th>الدرجة النهائية</th>
                            <th>الدرجة العملية</th>
                            <th>إجمالي الدرجة</th>
                            <th>الإجراء</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($degrees as $degree)
                            <tr>
                                <td>{{ $degree->session->course->course_name }}</td>
                                <td>{{ $degree->attendance_degree }}</td>
                                <td>{{ $degree->final_degree }}</td>
                                <td>{{ $degree->practical_degree }}</td>
                                <td>{{ $degree->total_degree }}</td>
                                <td>
                                    <!-- زر الطباعة -->
                                    <button class="btn btn-sm" style="background-color: #e94c21;color:white" onclick="window.print()">طباعة</button>
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
            
            <button class="btn btn-sm" style="background-color: #e94c21;color:white" onclick="window.print()">   طباعة الكل </button>

        </div>
    </div>
</div>
@endsection
