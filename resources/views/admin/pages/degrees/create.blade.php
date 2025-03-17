@extends('admin.layouts.app')

@section('title', 'إضافة درجات الطلاب')

@section('content')
<div class="page-wrapper" style="background-color: #F9F9FB;">
    <div class="content container-fluid">
        <div class="page-header">
            <h3 class="page-title">إضافة درجات الطلاب</h3>
        </div>

        <!-- 📋 نموذج إدخال الدرجات -->
        <div class="card shadow-sm p-4">
            <form method="POST" action="{{ route('degrees.store') }}">
                @csrf

                <div class="row">
                    <!-- 🔹 اختيار القسم -->
                    <div class="col-md-3">
                        <label>القسم</label>
                        <select name="department_id" id="department_id" class="form-control">
                            <option value="">-- اختر القسم --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 🔹 اختيار الكورس -->
                    <div class="col-md-3">
                        <label>الكورس</label>
                        <select name="course_id" id="course_id" class="form-control">
                            <option value="">-- اختر الكورس --</option>
                        </select>
                    </div>

                    <!-- 🔹 اختيار الجلسة -->
                    <div class="col-md-3">
                        <label>الجلسة</label>
                        <select name="session_id" id="session_id" class="form-control">
                            <option value="">-- اختر الجلسة --</option>
                        </select>
                    </div>
                </div>

                <!-- 📌 عرض الطلاب بعد اختيار الجلسة -->
                <div id="students-container" class="mt-4"></div>

                <div class="col-md-12 mt-4 text-center">
                    <button type="submit" class="btn btn-success">حفظ الدرجات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('department_id').addEventListener('change', function () {
        let departmentId = this.value;
        let courseSelect = document.getElementById('course_id');
        let sessionSelect = document.getElementById('session_id');
        let studentsContainer = document.getElementById('students-container');

        courseSelect.innerHTML = '<option value="">-- اختر الكورس --</option>';
        sessionSelect.innerHTML = '<option value="">-- اختر الجلسة --</option>';
        studentsContainer.innerHTML = '';

        if (departmentId) {
            fetch(`/get-courses/${departmentId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(course => {
                        let option = new Option(course.course_name, course.id);
                        courseSelect.add(option);
                    });
                });
        }
    });

    document.getElementById('course_id').addEventListener('change', function () {
        let courseId = this.value;
        let sessionSelect = document.getElementById('session_id');

        sessionSelect.innerHTML = '<option value="">-- اختر الجلسة --</option>';

        if (courseId) {
            fetch(`/get-sessions/${courseId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(session => {
                        let option = new Option(session.start_date + " - " + session.end_date, session.id);
                        sessionSelect.add(option);
                    });
                });
        }
    });

    document.getElementById('session_id').addEventListener('change', function () {
        let sessionId = this.value;
        let studentsContainer = document.getElementById('students-container');

        if (sessionId) {
            fetch(`/get-students/${sessionId}`)
                .then(response => response.text())
                .then(html => {
                    studentsContainer.innerHTML = html;
                });
        }
    });
</script>

@endsection
