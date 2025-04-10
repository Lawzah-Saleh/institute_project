@extends('admin.layouts.app')

@section('title', 'إضافة الحضور')

@section('content')
<div class="page-wrapper" style="background-color: #F9F9FB;">
    <div class="content container-fluid">
        <div class="page-header">
            <h3 class="page-title">إضافة الحضور</h3>
        </div>

        <!-- عرض الأخطاء إذا كانت موجودة -->
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

        <!-- فورم اختيار القسم والكورس والجلسة ورفع الحضور -->
        <form method="POST" action="{{ route('attendance.store') }}" class="mb-4">
            @csrf
            <div class="row">
                <!-- قسم -->
                <div class="col-md-4">
                    <label>القسم</label>
                    <select name="department_id" id="department_id" class="form-control">
                        <option value="">-- اختر القسم --</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- كورس -->
                <div class="col-md-4">
                    <label>الكورس</label>
                    <select name="course_id" id="course_id" class="form-control" {{ old('department_id') ? '' : 'disabled' }}>
                        <option value="">-- اختر الدورة --</option>
                        @if(old('department_id'))
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->course_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- جلسة -->
                <div class="col-md-4">
                    <label>الجلسة</label>
                    <select name="session_id" id="session_id" class="form-control" {{ old('course_id') ? '' : 'disabled' }}>
                        <option value="">-- اختر الدورة المتاحة --</option>
                        @if(old('course_id'))
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}" {{ old('session_id') == $session->id ? 'selected' : '' }}>
                                    {{ $session->start_date }} - {{ $session->end_date }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- زر إظهار الطلاب -->
                <div class="col-md-12 mt-3">
                    <button type="button" id="show-students-btn" class="btn"style="background-color: #196098;color: white;">إظهار الطلاب</button>
                </div>
            </div>

            <!-- Display Students if Session is Selected -->
            <div id="students-table" style="margin-top: 30px;">
                <h4>قائمة الطلاب</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>الطالب</th>
                            <th>حالة الحضور</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($students) && count($students) > 0)
                            @foreach($students as $student)
                                <tr>
                                    <td>{{ $student->student_name_ar }}</td>
                                    <td>
                                        <select name="status[{{ $student->id }}]" class="form-control">
                                            <option value="1" {{ isset($student->attendances[0]) && $student->attendances[0]->status == 1 ? 'selected' : '' }}>حاضر</option>
                                            <option value="0" {{ isset($student->attendances[0]) && $student->attendances[0]->status == 0 ? 'selected' : '' }}>غائب</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2">لا توجد طلاب في هذه الدورة الحالية .</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <input type="hidden" name="session_id" id="session_id_input" value="{{ old('session_id', $sessionId) }}">

            <!-- زر رفع الحضور -->
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn "style="background-color: #e94c21;color: white;">رفع الحضور</button>
            </div>
        </form>
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

// When session is selected, display students and save session_id to hidden field
document.getElementById('show-students-btn').addEventListener('click', function () {
    let sessionId = document.getElementById('session_id').value;
    let studentsTable = document.getElementById('students-table');

    if (sessionId) {
        fetch(`/get-students/${sessionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);  // Show error if no students found
                } else {
                    let tbody = document.querySelector("#students-table tbody");
                    tbody.innerHTML = '';  // Clear the table body
                    data.forEach(student => {
                        let row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${student.student_name_ar}</td>
                            <td>
                                <select name="status[${student.id}]" class="form-control">
                                    <option value="1">حاضر</option>
                                    <option value="0">غائب</option>
                                </select>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                    studentsTable.style.display = 'block';
                }
            })
            .catch(error => console.error('Error fetching students:', error));
    }
});

document.getElementById('session_id').addEventListener('change', function () {
    // عند اختيار الجلسة، نقوم بتحديث الحقل المخفي
    document.getElementById('session_id_input').value = this.value;
});

</script>

@endsection
