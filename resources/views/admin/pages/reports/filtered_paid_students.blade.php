@extends('admin.layouts.app')

@section('title', 'تقرير الطلاب المدفوعين')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <!-- العنوان -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تقرير الطلاب المدفوعين</h3>
                </div>
            </div>
        </div>

        <!-- فلاتر البحث -->
        <form method="GET" action="{{ route('admin.reports.filtered_paid_students') }}" class="mb-4">
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
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </div>
        </form>

        <!-- جدول التقرير -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">الطلاب المدفوعين</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>رقم الطالب</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>المبلغ المدفوع</th>
                            <th>إجمالي المبلغ</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td>{{ $student->student_name_ar }} ({{ $student->student_name_en }})</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ number_format($student->payments_sum_total_amount, 2) }} ريال</td>
                                <td>{{ number_format($student->invoices_sum_amount, 2) }} ريال</td>
                                <td>
                                    <a href="{{ route('admin.student.payment.details', $student->id) }}" class="btn btn-info btn-sm">عرض التفاصيل</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">لا توجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- زر التصدير -->
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{ route('admin.reports.export_excel', ['department_id' => request('department_id'), 'course_id' => request('course_id'), 'session_id' => request('session_id')]) }}" class="btn btn-success">
                    تصدير إلى Excel
                </a>
                <a href="{{ route('admin.reports.export_pdf', ['department_id' => request('department_id'), 'course_id' => request('course_id'), 'session_id' => request('session_id')]) }}" class="btn btn-danger">
                    تصدير إلى PDF
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    // جلب الكورسات بناءً على القسم المحدد
    $('#department_id').change(function() {
        var departmentId = $(this).val();
        if (departmentId) {
            $.get('/admin/get-courses/' + departmentId, function(data) {
                $('#course_id').html('<option value="">-- اختر الدورة --</option>');
                $.each(data, function(i, course) {
                    $('#course_id').append('<option value="' + course.id + '">' + course.course_name + '</option>');
                });
                $('#course_id').prop('disabled', false);
            });
        } else {
            $('#course_id').prop('disabled', true);
            $('#session_id').prop('disabled', true);
        }
    });

    // جلب الجلسات بناءً على الكورس المحدد
    $('#course_id').change(function() {
        var courseId = $(this).val();
        if (courseId) {
            $.get('/admin/get-sessions/' + courseId, function(data) {
                $('#session_id').html('<option value="">-- اختر الجلسة --</option>');
                $.each(data, function(i, session) {
                    $('#session_id').append('<option value="' + session.id + '">' + session.start_date + ' - ' + session.end_date + '</option>');
                });
                $('#session_id').prop('disabled', false);
            });
        } else {
            $('#session_id').prop('disabled', true);
        }
    });
</script>
@endsection
