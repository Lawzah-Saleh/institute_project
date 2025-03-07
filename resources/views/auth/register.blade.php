@extends('layouts.app')

@section('content')
<div class="container">
    <h2>تسجيل طالب جديد</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div>
            <label>الاسم (إنجليزي)</label>
            <input type="text" name="student_name_en" required>
        </div>

        <div>
            <label>الاسم (عربي)</label>
            <input type="text" name="student_name_ar" required>
        </div>

        <div>
            <label>رقم الهاتف</label>
            <input type="text" name="phone" required>
        </div>

        <div>
            <label>البريد الإلكتروني</label>
            <input type="email" name="email">
        </div>

        <div>
            <label>القسم</label>
            <select name="department_id" id="department" required>
                <option value="">اختر القسم</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>الكورس</label>
            <select name="course_id" id="courses" required>
                <option value="">اختر القسم أولًا</option>
            </select>
        </div>

        <div>
            <label>الوقت المناسب للدراسة</label>
            <input type="text" name="time" required>
        </div>

        <button type="submit">تسجيل</button>
    </form>
</div>

<script>
    document.getElementById('department').addEventListener('change', function() {
        var departmentId = this.value;
        fetch(`/get-courses/${departmentId}`)
            .then(response => response.json())
            .then(data => {
                var coursesSelect = document.getElementById('courses');
                coursesSelect.innerHTML = '<option value="">اختر الكورس</option>';
                data.forEach(course => {
                    coursesSelect.innerHTML += `<option value="${course.id}">${course.course_name}</option>`;
                });
            });
    });
</script>
@endsection
