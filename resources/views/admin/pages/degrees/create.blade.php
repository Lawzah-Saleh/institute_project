@extends('admin.layouts.app')

@section('title', 'إضافة الدرجات')

@section('content')
<div class="page-wrapper" style="background-color: #F9F9FB;">
    <div class="content container-fluid">
        <div class="page-header">
            <h3 class="page-title">إضافة الدرجات</h3>
        </div>

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

        <form method="GET" id="degreeForm">
            <div class="row">
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
                    <label>الدورة</label>
                    <select name="course_id" id="course_id" class="form-control" disabled>
                        <option value="">-- اختر الدورة --</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>الدورة الحالية</label>
                    <select name="session_id" id="session_id" class="form-control" disabled>
                        <option value="">-- اختر الدورة الحالية --</option>
                    </select>
                </div>

                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn"style="background-color: #196098;color: white;">عرض الطلاب</button>
                </div>
            </div>
        </form>

        @if(isset($students))
            <form action="{{ route('degrees.store') }}" method="POST">
                @csrf
                <input type="hidden" name="session_id" value="{{ $session->id }}">
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>الطالب</th>
                            <th>الدرجة العملية</th>
                            <th>الدرجة النهائية</th>
                            <th>درجة الحضور</th>
                            <th>المجموع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                        <tr>
                            <td>{{ $student->student_name_ar }}</td>
                            <td>
                                <input type="number" name="practical_degree[{{ $student->id }}]" class="form-control"
                                    value="{{ $student->degrees->first()->practical_degree ?? 0 }}" required>
                            </td>
                            <td>
                                <input type="number" name="final_degree[{{ $student->id }}]" class="form-control"
                                    value="{{ $student->degrees->first()->final_degree ?? 0 }}" required>
                            </td>
                            <td>
                                <input type="text" class="form-control" value="{{ $student->attendance_degree ?? 0 }}" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control" value="{{ $student->total_degree ?? 0 }}" readonly>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn "style="background-color: #e94c21;color: white;">حفظ الدرجات</button>
            </form>
        @endif
    </div>
</div>

<script>
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
document.getElementById('session_id').addEventListener('change', function () {
    let sessionId = this.value;
    let degreeForm = document.getElementById('degreeForm');

    if (sessionId) {
        degreeForm.action = `/admin/degrees/${sessionId}`;
    }
});

document.getElementById('degreeForm').addEventListener('submit', function (event) {
    let sessionId = document.getElementById('session_id').value;
    if (!sessionId) {
        event.preventDefault(); // منع إرسال الفورم إذا لم يتم تحديد الجلسة
        alert("يرجى اختيار جلسة قبل عرض الطلاب.");
    }
});

</script>
@endsection
