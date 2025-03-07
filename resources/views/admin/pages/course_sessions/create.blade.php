@extends('admin.layouts.app')

@section('title', 'إضافة جلسة جديدة')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">إضافة جلسة جديدة</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('course-sessions.index') }}">جلسات الكورسات</a></li>
                        <li class="breadcrumb-item active">إضافة جلسة</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('course-sessions.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="course_id">اختر الكورس:</label>
                                        <select name="course_id" id="course_id" class="form-control" required>
                                            <option value="" selected disabled>-- اختر الكورس --</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="employee_id">اختر المدرس:</label>
                                        <select name="employee_id" id="employee_id" class="form-control" required>
                                            <option value="" selected disabled>-- اختر المدرس --</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>عدد الساعات يوميًا:</label>
                                        <select name="daily_hours" class="form-control" required>
                                            <option value="2">ساعتان يوميًا</option>
                                            <option value="4">أربع ساعات يوميًا</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>تاريخ البداية:</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>تاريخ النهاية (يتم حسابه تلقائيًا):</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>وقت البداية:</label>
                                        <input type="time" name="start_time" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>وقت النهاية:</label>
                                        <input type="time" name="end_time" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>الحالة:</label>
                                        <select name="state" class="form-control" required>
                                            <option value="1" selected>نشطة</option>
                                            <option value="0">غير نشطة</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary">إضافة الجلسة</button>
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
document.getElementById('start_date').addEventListener('change', function() {
    let startDate = new Date(this.value);
    let courseId = document.getElementById('course_id').value;

    if (!courseId) {
        alert('يرجى اختيار الكورس أولًا لحساب تاريخ النهاية.');
        this.value = '';
        return;
    }

    fetch(`/api/get-course-duration/${courseId}`)
        .then(response => response.json())
        .then(data => {
            let totalHours = data.total_hours;
            let dailyHours = data.daily_hours;
            let daysNeeded = Math.ceil(totalHours / dailyHours);

            let endDate = new Date(startDate);
            let holidays = @json($holidays ?? []); // الحصول على الإجازات
            let addedDays = 0;

            while (addedDays < daysNeeded) {
                endDate.setDate(endDate.getDate() + 1);
                let dayOfWeek = endDate.getDay();
                let formattedDate = endDate.toISOString().split('T')[0];

                if (dayOfWeek !== 5 && !holidays.includes(formattedDate)) { // استثناء الجمعة والإجازات
                    addedDays++;
                }
            }

            document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
        });
});
</script>

@endsection
