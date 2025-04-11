@extends('admin.layouts.app')

@section('title', 'تقرير كشف بيانات الطلاب بالدورة')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <!-- العنوان -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تقرير كشف بيانات الطلاب بالدورة</h3>
                </div>
            </div>
        </div>

        <!-- فلاتر البحث -->
        <form method="GET" action="{{ route('admin.reports.students_in_course_report') }}" class="mb-4">
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
                    <label>اختر الدورة الحالية:</label>
                    <select name="session_id" id="session_id" class="form-control" {{ request('course_id') ? '' : 'disabled' }}>
                        <option value="">-- اختر الدورة الحالية --</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>
                                {{ $session->start_date }} - {{ $session->end_date }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- بحث عن الطالب -->
                <div class="col-md-8 mt-3">
                    <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو البريد أو الهاتف" value="{{ request('search') }}">
                </div>
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn " style="background-color: #196098;color: white">بحث</button>
                </div>
            </div>
        </form>

        <!-- جدول التقرير -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">بيانات الطلاب في الدورة</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>رقم الطالب</th>
                            <th>الاسم</th>
                            <th>رقم الهاتف</th>
                            <th>الدورة</th>
                            <th>الدورة الحالية</th>
                            <th>الوقت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td>{{ $student->student_name_ar }} ({{ $student->student_name_en }})</td>
                                <td>{{ is_array($phones = json_decode($student->phones, true)) ? implode(',', $phones) : 'غير متوفر' }}</td>
                                <td>
                                    @foreach($student->courses as $course)
                                        {{ $course->course_name }}
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($student->sessions as $session)
                                        {{ $session->start_date }} - {{ $session->end_date }}
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($student->sessions as $session)
                                        {{ $session->start_time }} - {{ $session->end_time }}
                                    @endforeach
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
                <a href="{{ route('admin.reports.export_excel_students_in_course', ['department_id' => request('department_id'), 'course_id' => request('course_id'), 'session_id' => request('session_id'), 'search' => request('search')]) }}" class="btn " style="background-color: #e94c21;color: white">
                    تصدير إلى Excel
                </a>
                <a href="{{ route('admin.reports.export_pdf_students_in_course', ['department_id' => request('department_id'), 'course_id' => request('course_id'), 'session_id' => request('session_id'), 'search' => request('search')]) }}" class="btn" style="background-color: #e94c21;color: white">
                    تصدير إلى PDF
                </a>
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
