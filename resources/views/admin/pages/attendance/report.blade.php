@extends('admin.layouts.app')

@section('title', 'تقرير الحضور')

@section('content')
<div class="page-wrapper" style="background-color: #F9F9FB; ">
    <div class="content container-fluid">
        <div class="page-header">
    <h3 class="page-title">📊 تقرير الحضور بالكورس</h3>

    <!-- 🏷️ اختيار القسم والكورس -->
    <div class="card shadow-sm p-4">
        <form method="GET" action="{{ route('attendance.report') }}">
            @csrf
            <div class="row">
                <!-- اختيار القسم -->
                <div class="col-md-4">
                    <label for="department_id">القسم</label>
                    <select name="department_id" id="department_id" class="form-control">
                        <option value="">-- اختر القسم --</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- اختيار الكورس -->
                <div class="col-md-4">
                    <label for="course_id">الكورس</label>
                    <select name="course_id" id="course_id" class="form-control" disabled>
                        <option value="">-- اختر الكورس --</option>
                        <!-- الكورسات ستظهر هنا بناءً على اختيار القسم -->
                    </select>
                </div>

                <!-- اختيار الجلسة -->
                <div class="col-md-4">
                    <label for="session_id">الجلسة</label>
                    <select name="session_id" id="session_id" class="form-control" disabled>
                        <option value="">-- اختر الجلسة --</option>
                        <!-- الجلسات ستظهر هنا بناءً على اختيار الكورس -->
                    </select>
                </div>

                <!-- اختيار نوع التقرير -->
                <div class="col-md-4 mt-3">
                    <label for="report_type">نوع التقرير</label>
                    <select name="report_type" id="report_type" class="form-control">
                        <option value="">-- اختر نوع التقرير --</option>
                        <option value="daily">يومي</option>
                        <option value="monthly">شهري</option>
                    </select>
                </div>

                <!-- اختيار التاريخ (لتقرير يومي) -->
                <div class="col-md-6 mt-3" id="daily-report-fields" style="display: none;">
                    <label for="attendance_day">التاريخ</label>
                    <input type="date" name="attendance_day" id="attendance_day" class="form-control" value="{{ old('attendance_day') }}">
                </div>

                <!-- اختيار الشهر والسنة (لتقرير شهري) -->
                <div class="col-md-6 mt-3" id="monthly-report-fields" style="display: none;">
                    <label for="month">الشهر</label>
                    <select name="month" class="form-control">
                        <option value="01" {{ old('month') == '01' ? 'selected' : '' }}>يناير</option>
                        <option value="02" {{ old('month') == '02' ? 'selected' : '' }}>فبراير</option>
                        <option value="03" {{ old('month') == '03' ? 'selected' : '' }}>مارس</option>
                        <option value="04" {{ old('month') == '04' ? 'selected' : '' }}>أبريل</option>
                        <!-- إضافة بقية الأشهر هنا -->
                    </select>
                </div>

                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">عرض التقرير</button>
                </div>
            </div>
        </form>
        <div class="card mt-4">
            <div class="card-header">
                <h5>نسبة الحضور</h5>
            </div>
            <div class="card-body">
                <h3>{{ number_format($attendancePercentage, 2) }}%</h3>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: {{ $attendancePercentage }}%;" aria-valuenow="{{ $attendancePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        @if(isset($attendances) && $attendances->count())
        <div class="card shadow-sm mt-4 p-4">
            <h5 class="mb-3">📌 بيانات الحضور</h5>
            <table class="table table-bordered table-hover text-center">            <thead>
                <tr>
                    <th>الطالب</th>
                    <th>الكورس</th>
                    <th>الجلسة</th>
                    <th>التاريخ</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->student->student_name_ar }}</td>
                        <td>{{ $attendance->session->course->course_name }}</td>
                        <td>{{ $attendance->session->start_date }} - {{ $attendance->session->end_date }}</td>
                        <td>{{ $attendance->attendance_date }}</td>
                        <td>
                            <span class="badge {{ $attendance->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $attendance->status ? 'حاضر' : 'غائب' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
                @else
                <p>لا توجد بيانات لعرضها.</p>
            @endif

            </tbody>
        </table>
    </div>
</div>

        <script>
            // التعامل مع اختيار القسم لتحميل الكورسات
            document.getElementById('department_id').addEventListener('change', function () {
                let departmentId = this.value;
                let courseSelect = document.getElementById('course_id');
                let sessionSelect = document.getElementById('session_id');

                courseSelect.innerHTML = '<option value="">-- اختر الكورس --</option>';
                sessionSelect.innerHTML = '<option value="">-- اختر الجلسة --</option>';
                courseSelect.disabled = true;
                sessionSelect.disabled = true;

                if (departmentId) {
                    fetch(`/get-courses/${departmentId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(course => {
                                let option = new Option(course.course_name, course.id);
                                courseSelect.add(option);
                            });
                            courseSelect.disabled = false;
                        });
                }
            });

            document.getElementById('course_id').addEventListener('change', function () {
            let courseId = this.value;
            let sessionSelect = document.getElementById('session_id');

            sessionSelect.innerHTML = '<option value="">-- اختر الجلسة --</option>';
            sessionSelect.disabled = true;

            if (courseId) {
                fetch(`/get-sessions/${courseId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(session => {
                            let option = new Option(session.start_date + " - " + session.end_date, session.id);
                            sessionSelect.add(option);
                        });
                        sessionSelect.disabled = false;
                    });
            }
        });


            // التعامل مع اختيار نوع التقرير
            document.getElementById('report_type').addEventListener('change', function () {
                let reportType = this.value;

                // إخفاء وإظهار الحقول بناءً على نوع التقرير
                if (reportType === 'daily') {
                    document.getElementById('daily-report-fields').style.display = 'block';
                    document.getElementById('monthly-report-fields').style.display = 'none';
                } else if (reportType === 'monthly') {
                    document.getElementById('daily-report-fields').style.display = 'none';
                    document.getElementById('monthly-report-fields').style.display = 'block';
                } else {
                    document.getElementById('daily-report-fields').style.display = 'none';
                    document.getElementById('monthly-report-fields').style.display = 'none';
                }
            });
        </script>

@endsection
