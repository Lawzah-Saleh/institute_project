@extends('admin.layouts.app')

@section('title', 'تقرير درجات الطلاب')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <!-- العنوان -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تقرير درجات الطلاب</h3>
                </div>
            </div>
        </div>

        <!-- فلاتر البحث -->
        <form method="GET" action="{{ route('admin.reports.students_grades_report') }}" class="mb-4">
            <div class="row">
                <!-- قسم -->
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
                <!-- دورة -->
                <div class="col-md-4">
                    <label>اختر الدورة:</label>
                    <select name="course_id" id="course_id" class="form-control" {{ request('department_id') ? '' : 'disabled' }}>
                        <option value="">-- اختر الدورة --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->course_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- جلسة -->
                <div class="col-md-4">
                    <label>اختر الدورة الحالية  :</label>
                    <select name="session_id" id="session_id" class="form-control" {{ request('course_id') ? '' : 'disabled' }}>
                        <option value="">-- اختر الدورة الحالية  --</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>
                                {{ $session->start_date }} - {{ $session->end_date }}
                            </option>
                        @endforeach
                    </select>
                </div>
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

        <!-- معلومات الدورة -->
        @if($sessionInfo)
        <div class="mb-4">
            <h5><strong>اسم الدورة:</strong> {{ $sessionInfo->course->course_name ?? '' }}</h5>
            <h6><strong>اسم المدرس:</strong> {{ $sessionInfo->employee->name_ar ?? 'غير محدد' }}</h6>
            <h6><strong>الوقت:</strong> {{ $sessionInfo->start_time }} - {{ $sessionInfo->end_time }}</h6>
        </div>
        @endif

        <!-- جدول التقرير -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">درجات الطلاب</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>رقم الطالب</th>
                            <th>اسم الطالب</th>
                            <th>درجة الحضور (من 10)</th>
                            <th>الدرجة الكلية</th>
                            <th>التقدير</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td>{{ $student->student_name_ar }} ({{ $student->student_name_en }})</td>
                                <td>{{ $student->degrees->firstWhere('course_session_id', request('session_id'))->attendance_degree ?? '0' }}</td>
                                <td>{{ $student->degrees->firstWhere('course_session_id', request('session_id'))->total_degree ?? '0' }}</td>
                                <td>
                                    @php
                                        $score = $student->degrees->firstWhere('course_session_id', request('session_id'))->total_degree ?? 0;
                                        if ($score >= 90) $grade = 'ممتاز';
                                        elseif ($score >= 80) $grade = 'جيد جداً';
                                        elseif ($score >= 70) $grade = 'جيد';
                                        elseif ($score >= 60) $grade = 'مقبول';
                                        else $grade = 'راسب';
                                    @endphp
                                    {{ $grade }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">لا توجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- زر التصدير -->
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{ route('admin.reports.export_excel_students_grades', ['department_id' => request('department_id'), 'course_id' => request('course_id'), 'session_id' => request('session_id'), 'search' => request('search')]) }}" class="btn" style="background-color: #e94c21;color: white">
                    تصدير إلى Excel
                </a>
                <button class="btn " style="background-color: #e94c21;color:white" onclick="window.print()">طباعة</button>

                {{-- <a href="{{ route('admin.reports.export_pdf_students_grades', ['department_id' => request('department_id'), 'course_id' => request('course_id'), 'session_id' => request('session_id'), 'search' => request('search')]) }}" class="btn" style="background-color: #e94c21;color: white">
                    تصدير إلى PDF
                </a> --}}
            </div>
        </div>

    </div>
</div>

<script>
    // جلب الدورات بناءً على القسم المحدد
    $('#department_id').change(function() {
        var departmentId = $(this).val();  // الحصول على قيمة القسم المحدد
        if (departmentId) {
            // إرسال طلب GET إلى السيرفر لجلب الدورات المرتبطة بالقسم المحدد
            $.get('/admin/get-courses/' + departmentId, function(data) {
                // مسح الخيارات السابقة في حقل الدورة
                $('#course_id').html('<option value="">-- اختر الدورة --</option>');
                
                // إضافة الدورات المستلمة من السيرفر إلى حقل الدورة
                $.each(data, function(i, course) {
                    $('#course_id').append('<option value="' + course.id + '">' + course.course_name + '</option>');
                });

                // تمكين حقل الدورة ليصبح قابل للاختيار
                $('#course_id').prop('disabled', false);
            });
        } else {
            // إذا لم يتم تحديد قسم، تعطيل حقل الدورة
            $('#course_id').prop('disabled', true);
            $('#session_id').prop('disabled', true); // تعطيل حقل الجلسات أيضًا
        }
    });

    // جلب الجلسات بناءً على الكورس المحدد
    $('#course_id').change(function() {
        var courseId = $(this).val();  // الحصول على قيمة الكورس المحدد
        if (courseId) {
            // إرسال طلب GET إلى السيرفر لجلب الجلسات المرتبطة بالكورس
            $.get('/admin/get-sessions/' + courseId, function(data) {
                // مسح الخيارات السابقة في حقل الجلسة
                $('#session_id').html('<option value="">-- اختر الجلسة --</option>');
                
                // إضافة الجلسات المستلمة من السيرفر إلى حقل الجلسة
                $.each(data, function(i, session) {
                    $('#session_id').append('<option value="' + session.id + '">' + session.start_date + ' - ' + session.end_date + '</option>');
                });

                // تمكين حقل الجلسة ليصبح قابل للاختيار
                $('#session_id').prop('disabled', false);
            });
        } else {
            // إذا لم يتم تحديد كورس، تعطيل حقل الجلسة
            $('#session_id').prop('disabled', true);
        }
    });
</script>

@endsection
