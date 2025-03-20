@extends('admin.layouts.app')

@section('title', 'إدارة الطلاب')

@section('content')
<div class="page-wrapper" style="margin-right: 100px;padding:50px 20px;width: calc(105% - 150px);background:#f7f7fa;">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">إدارة الطلاب</h3>
                </div>
            </div>
        </div>

        <!-- 🔍 Filters -->
        <form method="GET" action="{{ route('students.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>القسم</label>
                    <select name="department_id" id="department_id" class="form-control">
                        <option value="" selected>-- اختر القسم --</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>


                </div>

                <!-- اختيار الكورسات بناءً على القسم -->
                <div class="col-md-4">
                    <label>الكورس</label>
                    <select name="course_id" id="course_id" class="form-control" {{ request('department_id') ? '' : 'disabled' }}>
                        <option value="">-- اختر الكورس --</option>
                        @foreach($courses as $course)
                            <option value="" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->course_name }}
                            </option>
                        @endforeach
                    </select>

                </div>

                <!-- اختيار الجلسات بناءً على الكورس -->
                <div class="col-md-4">
                    <label>الجلسة</label>
                    <select name="course_session_id" id="session_id" class="form-control" {{ request('course_id') ? '' : 'disabled' }}>
                        <option value="">-- اختر الجلسة --</option>
                        @foreach($sessions as $session)
                            <option value="" {{ request('course_session_id') == $session->id ? 'selected' : '' }}>
                                {{ $session->start_date }} - {{ $session->end_date }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-8 mt-3">
                    <input type="text" name="search" class="form-control" placeholder="ابحث عن الطالب بالاسم أو الرقم" value="">
                </div>
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </div>
        </form>

        <!-- 📋 Students List -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">قائمة الطلاب</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ url('students/create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> إضافة طالب
                                    </a>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>رقم الطالب</th>
                                    <th>الاسم بالعربية</th>
                                    <th>الاسم بالإنجليزية</th>
                                    <th>رقم الهاتف</th>
                                    <th>الجنس</th>
                                    <th>المؤهل</th>
                                    <th>العنوان</th>
                                    <th>الحالة</th>
                                    <th>الكورس</th>
                                    <th>الجلسة</th>
                                    <th class="text-end">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                <tr>
                                    <td>{{ $student->id }}</td>
                                    <td>{{ $student->student_name_ar }}</td>
                                    <td>{{ $student->student_name_en }}</td>
                                    <td>
                                        @php $phones = json_decode($student->phones, true); @endphp
                                        {{ $phones ? implode(', ', $phones) : 'غير متوفر' }}
                                    </td>
                                    <td>{{ $student->gender == 'male' ? 'ذكر' : 'أنثى' }}</td>
                                    <td>{{ $student->qualification ?? 'غير محدد' }}</td>
                                    <td>{{ $student->address }}</td>
                                    <td>
                                        <span class="badge {{ $student->state ? 'bg-success' : 'bg-danger' }}">
                                            {{ $student->state ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($student->courses->isNotEmpty())
                                            <span >{{ $student->courses->first()->course_name }}</span>
                                        @elseif ($student->sessions->isNotEmpty())
                                            <span >{{ $student->sessions->first()->course->course_name ?? 'كورس غير معروف' }}</span>
                                        @else
                                            <span >غير مسجل</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($student->sessions->isNotEmpty())
                                            <span class="badge bg-primary">
                                                {{ $student->sessions->first()->start_date }} - {{ $student->sessions->first()->end_date }}
                                            </span>
                                        @else
                                            <span">لا توجد جلسات</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-success">
                                            <i class="feather-eye"></i> عرض
                                        </a>

                                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-success">
                                            <i class="feather-edit"></i> تعديل
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="text-center">لا يوجد طلاب مسجلين.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('department_id').addEventListener('change', function () {
    const departmentId = this.value;
    const courseSelect = document.getElementById('course_id');
    const sessionSelect = document.getElementById('session_id');

    courseSelect.innerHTML = '<option value="">-- اختر الكورس --</option>';
    sessionSelect.innerHTML = '<option value="">-- اختر الجلسة --</option>';
    courseSelect.disabled = true;
    sessionSelect.disabled = true;

    if (departmentId) {
        fetch(`/get-courses/${departmentId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = course.course_name;
                    courseSelect.appendChild(option);
                });
                courseSelect.disabled = false;
            })
            .catch(error => console.error('Error fetching courses:', error));
    }
});

document.getElementById('course_id').addEventListener('change', function () {
    const courseId = this.value;
    const sessionSelect = document.getElementById('session_id');

    sessionSelect.innerHTML = '<option value="">-- اختر الجلسة --</option>';
    sessionSelect.disabled = true;

    if (courseId) {
        fetch(`/get-sessions/${courseId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(session => {
                    const option = document.createElement('option');
                    option.value = session.id;
                    option.textContent = `${session.start_date} - ${session.end_date}`;
                    sessionSelect.appendChild(option);
                });
                sessionSelect.disabled = false;
            })
            .catch(error => console.error('Error fetching sessions:', error));
    }
});


</script>
@endsection
