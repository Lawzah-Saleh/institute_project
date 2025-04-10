@extends('admin.layouts.app')

@section('title', 'تقرير الحضور والغياب')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">
        <!-- العنوان -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تقرير الحضور والغياب</h3>
                </div>
            </div>
        </div>

        <!-- فلاتر البحث -->
        <form method="GET" action="{{ route('admin.reports.attendance_report') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>اختر القسم:</label>
                    <select name="department_id" id="department_id" class="form-control">
                        <option value="">-- اختر القسم --</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label>اختر الدورة:</label>
                    <select name="course_id" id="course_id" class="form-control" {{ request('department_id') ? '' : 'disabled' }}>
                        <option value="">-- اختر الدورة --</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label>اختر الجلسة:</label>
                    <select name="session_id" id="session_id" class="form-control" {{ request('course_id') ? '' : 'disabled' }}>
                        <option value="">-- اختر الجلسة --</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>اختر نوع التقرير:</label>
                    <select name="report_type" id="report_type" class="form-control" {{ request('session_id') ? '' : 'disabled' }}>
                        <option value="daily" {{ request('report_type') == 'daily' ? 'selected' : '' }}>تقرير يومي</option>
                        <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>تقرير شهري</option>
                    </select>
                </div>

            <div class="row mt-3">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو البريد أو الهاتف" value="{{ request('search') }}">
                </div>
                <div class="col-md-4 text-left">
                    <button type="submit" class="btn" style="background-color: #196098;color: white">بحث</button>
                </div>
            </div>
        </form>

        <!-- جدول التقرير -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">حضور الطلاب</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>رقم الطالب</th>
                            <th>اسم الطالب</th>
                            <th>الحضور</th>
                            @if(request('report_type') == 'monthly')
                                <th>نسبة الحضور</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td>{{ $student->student_name_ar }} ({{ $student->student_name_en }})</td>
                                <td>
                                    @if(request('report_type') == 'daily')
                                        {{ $student->sessions->contains('id', request('session_id')) ? 'حاضر' : 'غائب' }}
                                    @elseif(request('report_type') == 'monthly')
                                        {{-- عرض نسبة الحضور في الشهر --}}
                                        @php
                                            $attendedSessions = $student->sessions->whereBetween('start_date', [now()->startOfMonth(), now()->endOfMonth()]);
                                            $attendancePercentage = $attendedSessions->count() / $student->sessions->count() * 100;
                                        @endphp
                                        {{ number_format($attendancePercentage, 2) }}%
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        

        <!-- زر التصدير -->
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{ route('admin.reports.export_excel_attendance', ['department_id' => request('department_id'), 'course_id' => request('course_id'), 'session_id' => request('session_id'), 'search' => request('search')]) }}" class="btn" style="background-color: #e94c21;color: white">
                    تصدير إلى Excel
                </a>
                <a href="{{ route('admin.reports.export_pdf_attendance', ['department_id' => request('department_id'), 'course_id' => request('course_id'), 'session_id' => request('session_id'), 'search' => request('search')]) }}" class="btn" style="background-color: #e94c21;color: white">
                    تصدير إلى PDF
                </a>
            </div>
        </div>

    </div>
</div>
<script>
 $(document).ready(function() {
    // When the department is changed
    $('#department_id').change(function() {
        var departmentId = $(this).val();  // Get the selected department ID

        if (departmentId) {
            // Make an AJAX GET request to fetch courses based on the department
            $.get('/admin/get-courses/' + departmentId, function(data) {
                // Clear existing options in the course dropdown
                $('#course_id').html('<option value="">-- اختر الدورة --</option>');
                
                // Append the fetched courses as options
                $.each(data, function(i, course) {
                    $('#course_id').append('<option value="' + course.id + '">' + course.course_name + '</option>');
                });

                // Enable the course dropdown
                $('#course_id').prop('disabled', false);
            });
        } else {
            // If no department selected, disable the course and session dropdown
            $('#course_id').prop('disabled', true);
            $('#session_id').prop('disabled', true);
        }
    });

    // When the course is changed
    $('#course_id').change(function() {
        var courseId = $(this).val();  // Get the selected course ID

        if (courseId) {
            // Make an AJAX GET request to fetch sessions based on the course
            $.get('/admin/get-sessions/' + courseId, function(data) {
                // Clear existing options in the session dropdown
                $('#session_id').html('<option value="">-- اختر الجلسة --</option>');

                // Append the fetched sessions as options
                $.each(data, function(i, session) {
                    $('#session_id').append('<option value="' + session.id + '">' + session.start_date + ' - ' + session.end_date + '</option>');
                });

                // Enable the session dropdown
                $('#session_id').prop('disabled', false);
            });
        } else {
            // If no course selected, disable the session dropdown
            $('#session_id').prop('disabled', true);
        }
    });
});


    </script>
@endsection
