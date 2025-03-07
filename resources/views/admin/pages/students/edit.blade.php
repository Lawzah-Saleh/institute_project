@extends('admin.layouts.app')

@section('title', 'تعديل بيانات الطالب')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تعديل بيانات الطالب</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
                        <li class="breadcrumb-item active">تعديل بيانات الطالب</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card comman-shadow">
            <div class="card-body">
                <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- الاسم بالعربية -->
                        <div class="col-md-6 mb-3">
                            <label>الاسم بالعربية <span class="text-danger">*</span></label>
                            <input type="text" name="student_name_ar" class="form-control" value="{{ $student->student_name_ar }}" required>
                        </div>

                        <!-- الاسم بالإنجليزية -->
                        <div class="col-md-6 mb-3">
                            <label>الاسم بالإنجليزية <span class="text-danger">*</span></label>
                            <input type="text" name="student_name_en" class="form-control" value="{{ $student->student_name_en }}" required>
                        </div>

                        <!-- الهاتف -->
                        <div class="col-md-6 mb-3">
                            <label>رقم الهاتف <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" value="{{ $student->phone }}" required>
                        </div>

                        <!-- الجنس -->
                        <div class="col-md-6 mb-3">
                            <label>الجنس <span class="text-danger">*</span></label>
                            <select name="gender" class="form-control" required>
                                <option value="male" {{ $student->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                                <option value="female" {{ $student->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                            </select>
                        </div>

                        <!-- المؤهل -->
                        <div class="col-md-6 mb-3">
                            <label>المؤهل <span class="text-danger">*</span></label>
                            <input type="text" name="qualification" class="form-control" value="{{ $student->qualification }}" required>
                        </div>

                        <!-- تاريخ الميلاد -->
                        <div class="col-md-6 mb-3">
                            <label>تاريخ الميلاد <span class="text-danger">*</span></label>
                            <input type="date" name="birth_date" class="form-control" value="{{ $student->birth_date }}" required>
                        </div>

                        <!-- مكان الميلاد -->
                        <div class="col-md-6 mb-3">
                            <label>مكان الميلاد <span class="text-danger">*</span></label>
                            <input type="text" name="birth_place" class="form-control" value="{{ $student->birth_place }}" required>
                        </div>

                        <!-- العنوان -->
                        <div class="col-md-6 mb-3">
                            <label>العنوان <span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control" value="{{ $student->address }}" required>
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div class="col-md-6 mb-3">
                            <label>البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ $student->email }}" required>
                        </div>

                        <!-- الحالة -->
                        <div class="col-md-6 mb-3">
                            <label>الحالة <span class="text-danger">*</span></label>
                            <select name="state" class="form-control" required>
                                <option value="1" {{ $student->state ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ !$student->state ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>

                        <!-- صورة الطالب -->
                        <div class="col-md-6 mb-3">
                            <label>صورة الطالب</label>
                            <input type="file" name="image" class="form-control">
                            @if($student->image)
                                <img src="{{ asset('storage/' . $student->image) }}" width="80" class="mt-2">
                            @endif
                        </div>
                                                <!-- اختيار القسم -->
                        <div class="col-md-6 mb-3">
                            <label>اختر القسم</label>
                            <select name="department_id" id="department_id" class="form-control">
                                <option value="">-- اختر القسم --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ $student->department_id == $department->id ? 'selected' : '' }}>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- اختيار الكورس -->
                        <div class="col-md-6 mb-3">
                            <label>اختر الكورس</label>
                            <select name="course_id" id="course_id" class="form-control">
                                <option value="">-- اختر الكورس --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ $student->course_id == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- اختيار الجلسة -->
                        <div class="col-md-6 mb-3">
                            <label>اختر الجلسة</label>
                            <select name="session_id" id="session_id" class="form-control">
                                <option value="">-- اختر الجلسة --</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ $student->session_id == $session->id ? 'selected' : '' }}>
                                        {{ $session->start_date }} - {{ $session->end_date }} ({{ $session->start_time }} - {{ $session->end_time }})
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <!-- أزرار الحفظ والإلغاء -->
                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                            <a href="{{ route('students.index') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
<script>
    document.getElementById('department_id').addEventListener('change', function () {
        const departmentId = this.value;
        const courseSelect = document.getElementById('course_id');
        const sessionSelect = document.getElementById('session_id');

        // تفريغ الخيارات السابقة
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

        // تفريغ الجلسات السابقة
        sessionSelect.innerHTML = '<option value="">-- اختر الجلسة --</option>';
        sessionSelect.disabled = true;

        if (courseId) {
            fetch(`/get-sessions/${courseId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(session => {
                        const option = document.createElement('option');
                        option.value = session.id;
                        option.textContent = `${session.start_date} - ${session.end_date} (${session.start_time} - ${session.end_time})`;
                        sessionSelect.appendChild(option);
                    });
                    sessionSelect.disabled = false;
                })
                .catch(error => console.error('Error fetching sessions:', error));
        }
    });
</script>

@endsection
