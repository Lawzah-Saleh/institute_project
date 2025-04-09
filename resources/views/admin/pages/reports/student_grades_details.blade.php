@extends('admin.layouts.app')

@section('title', 'بيان درجات الطالب')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <!-- العنوان -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">بيان درجات الطالب</h3>
                </div>
            </div>
        </div>

        <!-- بيانات الطالب -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">معلومات الطالب:</h5>
                <p><strong>الاسم:</strong> {{ $student->student_name_ar }} ({{ $student->student_name_en }})</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $student->email }}</p>
                <p><strong>رقم الهاتف:</strong> {{ is_array($phones = json_decode($student->phones, true)) ? implode(',', $phones) : 'غير متوفر' }}</p>
            </div>
        </div>

        <!-- تفاصيل الدرجات -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">تفاصيل الدرجات:</h5>
                <p><strong>الدرجة الكلية:</strong> {{ $totalGrade }} من 100</p>
                <p><strong>درجة الحضور (من 10):</strong> {{ $attendanceGrade }}</p>
                <p><strong>التقدير النهائي:</strong> {{ $finalGrade }}</p>
            </div>
        </div>

        <!-- جدول الجلسات والدورات -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">الدورات والجلسات:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>اسم الدورة</th>
                            <th>الجلسة</th>
                            <th>الوقت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                            @foreach($sessions as $session)
                                <tr>
                                    <td>{{ $course->course_name }}</td>
                                    <td>{{ $session->start_date }} - {{ $session->end_date }}</td>
                                    <td>{{ $session->start_time }} - {{ $session->end_time }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- زر التصدير -->
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{ route('admin.reports.export_excel_student_grades', ['studentId' => $student->id]) }}" class="btn btn-success">
                    تصدير إلى Excel
                </a>
                <a href="{{ route('admin.reports.export_pdf_student_grades', ['studentId' => $student->id]) }}" class="btn btn-danger">
                    تصدير إلى PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
