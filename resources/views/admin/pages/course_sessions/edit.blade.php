@extends('admin.layouts.app')

@section('title', 'تعديل الجلسة')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تعديل الجلسة</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('course-sessions.index') }}">جلسات الكورسات</a></li>
                        <li class="breadcrumb-item active">تعديل الجلسة</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('course-sessions.update', $session->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="course_id">الكورس الحالي:</label>
                                        <select name="course_id" id="course_id" class="form-control" required>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}" {{ $session->course_id == $course->id ? 'selected' : '' }}>
                                                    {{ $course->course_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="employee_id">الأستاذ الحالي:</label>
                                        <select name="employee_id" id="employee_id" class="form-control" required>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ $session->employee_id == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>تاريخ البداية:</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $session->start_date }}" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>تاريخ النهاية (يتم تحديثه تلقائيًا):</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $session->end_date }}" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>وقت البداية:</label>
                                        <input type="time" name="start_time" class="form-control" value="{{ $session->start_time }}" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>وقت النهاية:</label>
                                        <input type="time" name="end_time" class="form-control" value="{{ $session->end_time }}" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>عدد الساعات يوميًا:</label>
                                        <select name="daily_hours" id="daily_hours" class="form-control" required>
                                            <option value="2" {{ $session->daily_hours == 2 ? 'selected' : '' }}>ساعتان يوميًا</option>
                                            <option value="4" {{ $session->daily_hours == 4 ? 'selected' : '' }}>أربع ساعات يوميًا</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>الحالة:</label>
                                        <select name="state" class="form-control" required>
                                            <option value="1" {{ $session->state ? 'selected' : '' }}>نشطة</option>
                                            <option value="0" {{ !$session->state ? 'selected' : '' }}>غير نشطة</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary">تعديل الجلسة</button>
                                    <a href="{{ route('course-sessions.index') }}" class="btn btn-secondary">إلغاء</a>
                                </div>
                            </div>
                        </form>
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div> <!-- col-sm-12 -->
        </div> <!-- row -->

    </div> <!-- content -->
</div> <!-- page-wrapper -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let startDateInput = document.getElementById("start_date");
        let dailyHoursInput = document.getElementById("daily_hours");
        let endDateInput = document.getElementById("end_date");
        let courseIdInput = document.getElementById("course_id");

        function updateEndDate() {
            let startDate = new Date(startDateInput.value);
            let courseId = courseIdInput.value;
            let dailyHours = dailyHoursInput.value;

            if (!startDateInput.value || !courseId || !dailyHours) {
                endDateInput.value = "";
                return;
            }

            fetch(`/api/get-course-duration/${courseId}`)
                .then(response => response.json())
                .then(data => {
                    let totalHours = data.total_hours;
                    let daysNeeded = Math.ceil(totalHours / dailyHours);

                    let endDate = new Date(startDate);
                    let holidays = @json($holidays ?? []);
                    let addedDays = 0;

                    while (addedDays < daysNeeded) {
                        endDate.setDate(endDate.getDate() + 1);
                        let dayOfWeek = endDate.getDay();
                        let formattedDate = endDate.toISOString().split('T')[0];

                        if (dayOfWeek !== 5 && !holidays.includes(formattedDate)) {
                            addedDays++;
                        }
                    }

                    endDateInput.value = endDate.toISOString().split('T')[0];
                })
                .catch(error => console.error("Error fetching course duration:", error));
        }

        startDateInput.addEventListener("change", updateEndDate);
        dailyHoursInput.addEventListener("change", updateEndDate);
    });
    </script>

@endsection
