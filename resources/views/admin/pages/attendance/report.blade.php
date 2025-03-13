@extends('admin.layouts.app')

@section('title', 'تقرير الحضور')

@section('content')
<div class="container mt-4">
    <h3 class="text-center mb-4">📊 تقرير الحضور بالكورس</h3>

    <!-- 🏷️ اختيار القسم والكورس -->
    <div class="card shadow-sm p-4">
        <form method="GET" action="{{ route('attendance.report') }}" class="row g-3">
            <!-- قسم -->
            <div class="col-md-4">
                <label>القسم</label>
                <select name="department_id" id="department_id" class="form-control">
                    <option value="">-- اختر القسم --</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>الكورس</label>
                <select name="course_id" id="course_id" class="form-control" disabled>
                    <option value="">-- اختر الكورس --</option>
                </select>
            </div>

            <div class="col-md-4">
                <label>الجلسة</label>
                <select name="session_id" id="session_id" class="form-control" disabled>
                    <option value="">-- اختر الجلسة --</option>
                </select>
            </div>

            <!-- زر عرض التقرير -->
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> عرض التقرير</button>
            </div>
        </form>
    </div>

    <!-- 📌 إحصائيات الحضور على شكل أعمدة -->
    <div class="card shadow-sm mt-4 p-4">
        <h5 class="mb-3">📌 إحصائيات الحضور</h5>
        <canvas id="attendanceChart"></canvas>
    </div>

</div>

<!-- 🔹 جلب الجلسات وعرض الحضور -->
<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');

    const attendanceData = @json($attendanceData); // بيانات الحضور من الخادم
    const labels = attendanceData.map(data => data.session);
    const presentData = attendanceData.map(data => data.present);
    const absentData = attendanceData.map(data => data.absent);

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'عدد الحضور',
                data: presentData,
                backgroundColor: '#28a745', // أخضر
                borderColor: '#28a745',
                borderWidth: 1
            }, {
                label: 'عدد الغياب',
                data: absentData,
                backgroundColor: '#dc3545', // أحمر
                borderColor: '#dc3545',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
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
</script>
@endsection
