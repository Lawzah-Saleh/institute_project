@extends('admin.layouts.app')

@section('title', 'إدارة الحضور')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <h3 class="page-title">إدارة الحضور</h3>
        </div>

        <!-- 🔍 البحث -->
        <form method="GET" action="{{ route('admin.attendance.index') }}" class="mb-4">
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
                <div class="col-md-4 mt-3">
                    <label>البحث باسم الكورس</label>
                    <input type="text" name="course_name" class="form-control" placeholder="أدخل اسم الكورس">
                </div>

                <div class="col-md-4 mt-3">
                    <label>الحالة</label>
                    <select name="status" class="form-control">
                        <option value="">-- اختر الحالة --</option>
                        <option value="1">حاضر</option>
                        <option value="0">غائب</option>
                    </select>
                </div>

                <div class="col-md-4 mt-3">
                    <label>ابحث عن الطالب</label>
                    <input type="text" name="student_name" class="form-control" placeholder="ابحث عن اسم الطالب">
                </div>

                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </div>
        </form>

        <!-- 📋 عرض قائمة الحضور -->
        <table class="table table-striped">
            <thead>
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
                    <td>
                        
                        <!-- 🔹 زر تعديل -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAttendanceModal-{{ $attendance->id }}">
                            تعديل
                        </button>
                
                        <!-- 🔹 نموذج تعديل الحضور -->
                        <div class="modal fade" id="editAttendanceModal-{{ $attendance->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('attendance.update', $attendance->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">تعديل الحضور</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label>الحالة</label>
                                            <select name="status" class="form-control">
                                                <option value="1" {{ $attendance->status ? 'selected' : '' }}>حاضر</option>
                                                <option value="0" {{ !$attendance->status ? 'selected' : '' }}>غائب</option>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                

                    </td>
                </tr>
                @endforeach
                {{-- <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">
                    إضافة حضور
                </button>
                
                <!-- 🔹 نافذة إدخال الحضور -->
                <div class="modal fade" id="addAttendanceModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">إضافة حضور</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('attendance.store') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label>الطالب</label>
                                        <select name="student_id" class="form-control" required>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}">{{ $student->student_name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>الجلسة</label>
                                        <select name="session_id" class="form-control" required>
                                            @foreach($sessions as $session)
                                                <option value="{{ $session->id }}">{{ $session->start_date }} - {{ $session->end_date }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>الحالة</label>
                                        <select name="status" class="form-control">
                                            <option value="1">حاضر</option>
                                            <option value="0">غائب</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">إضافة</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> --}}
                
                
            </tbody>
        </table>

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
</script>

@endsection
