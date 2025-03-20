@extends('admin.layouts.app')

@section('title', 'إدارة الدرجات')

@section('content')
<div class="page-wrapper" style="background-color: #F9F9FB;">
    <div class="content container-fluid">
        <div class="page-header">
            <h3 class="page-title">إدارة الدرجات</h3>
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
        <!-- 🔍 البحث -->
        <form method="GET" action="{{ route('degrees.index') }}" class="mb-4">
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
                    <label>البحث باسم الطالب</label>
                    <input type="text" name="student_name" class="form-control" placeholder="أدخل اسم الطالب">
                </div>

                <div class="col-md-4 mt-3">
                    <label>الحالة</label>
                    <select name="status" class="form-control">
                        <option value="">-- اختر الحالة --</option>
                        <option value="pass">ناجح</option>
                        <option value="fail">راسب</option>
                    </select>
                </div>

                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </div>
        </form>

        <!-- 📋 عرض قائمة الدرجات -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>الطالب</th>
                    <th>الكورس</th>
                    <th>الجلسة</th>
                    <th>درجة العملي</th>
                    <th>درجة النهائي</th>
                    <th>درجة الحضور</th>
                    <th>الدرجة الكلية</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($degrees as $degree)
                <tr>
                    <td>{{ $degree->student->student_name_ar }}</td>
                    <td>{{ $degree->session->course->course_name }}</td>
                    <td>{{ $degree->session->start_date }} - {{ $degree->session->end_date }}</td>
                    <td>{{ $degree->practical_degree }}</td>
                    <td>{{ $degree->final_degree }}</td>
                    <td>{{ $degree->attendance_degree }}</td>
                    <td>{{ $degree->total_degree }}</td>
                    <td>
                        <span class="badge {{ $degree->status == 'pass' ? 'bg-success' : 'bg-danger' }}">
                            {{ $degree->status == 'pass' ? 'ناجح' : 'راسب' }}
                        </span>
                    </td>
                    <td>

                        <!-- 🔹 زر تعديل -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editDegreeModal-{{ $degree->id }}">
                            تعديل
                        </button>

                        <!-- 🔹 نموذج تعديل الدرجات -->
                        <div class="modal fade" id="editDegreeModal-{{ $degree->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('degrees.update', $degree->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">تعديل الدرجات</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label>درجة العملي</label>
                                            <input type="number" name="practical_degree" class="form-control" value="{{ $degree->practical_degree }}" required>

                                            <label>درجة النهائي</label>
                                            <input type="number" name="final_degree" class="form-control" value="{{ $degree->final_degree }}" required>

                                            <label>درجة الحضور</label>
                                            <input type="number" name="attendance_degree" class="form-control" value="{{ $degree->attendance_degree }}" required>
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
