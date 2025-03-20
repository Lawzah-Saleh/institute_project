@extends('admin.layouts.app')

@section('title', 'إضافة درجات الطلاب')

@section('content')
<div class="container mt-4">
    <h3 class="text-center mb-4">📊 إضافة درجات الطلاب</h3>

    <div class="card shadow-sm p-4">
        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
        <form method="GET" action="{{ route('degrees.create') }}">
            @csrf
            <div class="row">
                <!-- اختيار القسم -->
                <div class="col-md-4">
                    <label for="department_id" class="form-label">اختر القسم:</label>
                    <select name="department_id" id="department_id" class="form-select">
                        <option value="">-- اختر القسم --</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- اختيار الكورس -->
                <div class="col-md-4">
                    <label for="course_id" class="form-label">اختر الكورس:</label>
                    <select name="course_id" id="course_id" class="form-select" disabled>
                        <option value="">-- اختر الكورس --</option>
                    </select>
                </div>

                <!-- اختيار الجلسة -->
                <div class="col-md-4">
                    <label for="session_id" class="form-label">اختر الجلسة:</label>
                    <select name="session_id" id="session_id" class="form-select" disabled>
                        <option value="">-- اختر الجلسة --</option>
                    </select>
                </div>

                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">عرض الطلاب</button>
                </div>
            </div>
        </form>
    </div>

    <!-- عرض الطلاب لإدخال الدرجات -->
    @if(isset($students) && $students->count() > 0)
    <form method="POST" action="{{ route('degrees.store') }}">
        @csrf
        <input type="hidden" name="session_id" value="{{ old('session_id') }}">

        <div class="card shadow-sm mt-4 p-4">
            <h5 class="mb-3">📌 درجات الطلاب</h5>
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>📛 رقم الطالب</th>
                        <th>📛 اسم الطالب</th>
                        <th>🎓 درجة الامتحان النهائي</th>
                        <th>💡 درجة العملية</th>
                        <th>📝 درجة الحضور</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>

                        <td>{{ $student->student_name_ar }}</td>

                        <td><input type="number" name="final_degree[{{ $student->id }}]" class="form-control" value="{{ old('final_degree.'.$student->id) }}" step="any"></td>
                        <td><input type="number" name="practical_degree[{{ $student->id }}]" class="form-control" value="{{ old('practical_degree.'.$student->id) }}" step="any"></td>
                        <td><input type="number" name="attendance_degree[{{ $student->id }}]" class="form-control" value="{{ $student->attendance_degree }}" disabled></td>

                        <input type="hidden" name="session_id" value="{{ old('session_id', $sessionId) }}">
                        <td><input type="hidden" name="student_id[]" value="{{ $student->id }}"></td>


                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="col-12 text-center mt-3">
                <button type="submit" class="btn btn-success">تسجيل الدرجات</button>
            </div>
        </div>
    </form>
    @endif
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
                courseSelect.disabled = false; // Enable the course select
            })
            .catch(error => console.error('Error fetching courses:', error));
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
                sessionSelect.disabled = false; // Enable the session select
            })
            .catch(error => console.error('Error fetching sessions:', error));
    }
});

</script>
@endsection

