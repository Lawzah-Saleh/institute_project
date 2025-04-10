@extends('admin.layouts.app')

@section('title', 'تسجيل الطالب في الدورة التالية')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:30px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <div class="page-header">
            <h3 class="page-title">تسجيل الطالب في الدورة التالية</h3>
        </div>

        <!-- نموذج التسجيل -->
        <form action="{{ route('students.register_next_course') }}" method="POST">
            @csrf

            <!-- 🔍 البحث عن الطالب -->
            <div class="form-group mb-4">
                <label>اسم الطالب:</label>
                <input type="text" id="student_search" class="form-control" placeholder="ابحث باسم الطالب..." autocomplete="off">
                <input type="hidden" name="student_id" id="student_id">
                <div id="search-results" class="list-group mt-2"></div>
            </div>

            <!-- اختيار الكورس -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>الدورة التالية:</label>
                    <select name="course_id" id="course_id" class="form-control" required>
                        <option value="">-- اختر الدورة --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label>الجلسة (اختياري):</label>
                    <select name="course_session_id" id="session_id" class="form-control">
                        <option value="">-- اختر الجلسة --</option>
                    </select>
                </div>
            </div>

            <!-- بيانات الدفع -->
            <div class="row">
                <div class="col-md-4">
                    <label>المبلغ المدفوع:</label>
                    <input type="number" step="0.01" name="amount_paid" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>وقت الدراسة:</label>
                    <select name="study_time" class="form-control">
                        <option value="8-10">8 - 10</option>
                        <option value="10-12">10 - 12</option>
                        <option value="12-2">12 - 2</option>
                        <option value="2-4">2 - 4</option>
                        <option value="4-6">4 - 6</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>طريقة الدفع:</label>
                    <select name="payment_method" class="form-control" required>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method->name }}">{{ $method->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <button class="btn" style="background-color: #196098; color:white">تسجيل في الدورة التالية</button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
document.getElementById('student_search').addEventListener('input', function () {
    let query = this.value;
    if (query.length < 2) return;

    fetch(`/search-students?q=${query}`)
        .then(response => response.json())
        .then(data => {
            let results = document.getElementById('search-results');
            results.innerHTML = '';
            data.forEach(student => {
                let item = document.createElement('a');
                item.className = 'list-group-item list-group-item-action';
                item.textContent = student.student_name_ar + ' (' + student.student_name_en + ')';
                item.setAttribute('data-id', student.id);
                item.addEventListener('click', function () {
                    document.getElementById('student_search').value = this.textContent;
                    document.getElementById('student_id').value = this.getAttribute('data-id');
                    results.innerHTML = '';
                });
                results.appendChild(item);
            });
        });
});

// تحميل الجلسات بناءً على الدورة
document.getElementById('course_id').addEventListener('change', function () {
    let courseId = this.value;
    let sessionSelect = document.getElementById('session_id');

    sessionSelect.innerHTML = '<option value="">-- اختر الجلسة --</option>';
    if (!courseId) return;

    fetch(`/get-sessions/${courseId}`)
        .then(response => response.json())
        .then(data => {
            data.forEach(session => {
                let option = document.createElement('option');
                option.value = session.id;
                option.textContent = `${session.start_date} - ${session.end_date}`;
                sessionSelect.appendChild(option);
            });
        });
});
</script>
@endsection
