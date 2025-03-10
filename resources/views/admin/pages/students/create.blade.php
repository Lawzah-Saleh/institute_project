@extends('admin.layouts.app')

@section('title', 'إضافة طالب جديد')

@section('content')

<div class="page-wrapper" style="background-color: #F9F9FB;">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">إضافة طالب جديد</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
                            <li class="breadcrumb-item active">إضافة طالب</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card comman-shadow " style="background-color: white;">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Arabic Name -->
                        <div class="col-md-6 mb-3">
                            <label>الاسم بالعربية <span class="text-danger">*</span></label>
                            <input type="text" name="student_name_ar" class="form-control" placeholder="أدخل الاسم بالعربية" value="{{ old('student_name_ar') }}" required>
                        </div>

                        <!-- English Name -->
                        <div class="col-md-6 mb-3">
                            <label>الاسم بالإنجليزية <span class="text-danger">*</span></label>
                            <input type="text" name="student_name_en" class="form-control" placeholder="Enter name in English" value="{{ old('student_name_en') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>رقم الهاتف <span class="text-danger">*</span></label>
                            <div id="phone-container">
                                <input type="text" name="phones[]" class="form-control" placeholder="أدخل رقم الهاتف" required>
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary mt-2" id="add-phone">+ إضافة رقم آخر</button>
                        </div>


                        <!-- Gender -->
                        <div class="col-md-6 mb-3">
                            <label>الجنس <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                            </select>
                        </div>

                        <!-- Qualification -->
                        <div class="col-md-6 mb-3">
                            <label>المؤهل <span class="text-danger">*</span></label>
                            <input type="text" name="qualification" class="form-control" placeholder="أدخل المؤهل" value="{{ old('qualification') }}" required>
                        </div>


                        <!-- Birth Date -->
                        <div class="col-md-6 mb-3">
                            <label>تاريخ الميلاد <span class="text-danger">*</span></label>
                            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
                        </div>

                        <!-- Birth Place -->
                        <div class="col-md-6 mb-3">
                            <label>مكان الميلاد <span class="text-danger">*</span></label>
                            <input type="text" name="birth_place" class="form-control" placeholder="أدخل مكان الميلاد" value="{{ old('birth_place') }}" required>
                        </div>

                        <!-- Address -->
                        <div class="col-md-6 mb-3">
                            <label>العنوان <span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control" placeholder="أدخل العنوان" value="{{ old('address') }}" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label>البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="example@email.com" value="{{ old('email') }}" >
                        </div>

                     <!-- اختيار القسم -->
                     <div class="col-md-6 mb-3">
                        <label>اختر القسم <span class="text-danger">*</span></label>
                        <select name="department_id" id="department_id" class="form-select" required>
                            <option value="">-- اختر القسم --</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                            @endforeach
                        </select>

                    </div>


                        <!-- اختيار الكورس -->
                        <div class="col-md-6 mb-3">
                            <label>اختر الكورس <span class="text-danger">*</span></label>
                            <select name="course_id" id="course_id" class="form-select" disabled>
                                <option value="">-- اختر الكورس --</option>
                            </select>
                        </div>
                        <!-- Study Time Selection -->
                        <div class="col-md-6 mb-3" id="study_time_container" style="display: none;">
                            <label>اختر وقت الدراسة <span class="text-danger">*</span></label>
                            <select name="study_time" class="form-select">
                                <option value="8-10">08:00 - 10:00 صباحًا</option>
                                <option value="10-12">10:00 - 12:00 ظهرًا</option>
                                <option value="12-2">12:00 - 02:00 ظهرًا</option>
                                <option value="2-4">02:00 - 04:00 عصرًا</option>
                                <option value="4-6">04:00 - 06:00 مساءً</option>
                            </select>
                            
                        </div>


                        <!-- اختيار الجلسة -->
                        <div class="col-md-6 mb-3">
                            <label>اختر الجلسة <span class="text-danger">*</span></label>
                            <select name="course_session_id" id="course_session_id" class="form-select" disabled>
                                <option value="">-- اختر الجلسة --</option>
                            </select>
                        </div>

                        <!-- State -->
                        <div class="col-md-6 mb-3">
                            <label>الحالة <span class="text-danger">*</span></label>
                            <select name="state" class="form-select" required>
                                <option value="1">نشط</option>
                                <option value="0">غير نشط</option>
                            </select>
                        </div>

                        <!-- Image -->
                        <div class="col-md-6 mb-3">
                            <label>صورة الطالب</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <!-- Submit -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">حفظ</button>
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
    const sessionSelect = document.getElementById('course_session_id');

    // Reset options
    courseSelect.innerHTML = '<option value="">-- اختر الكورس --</option>';
    sessionSelect.innerHTML = '<option value="">-- اختر الجلسة --</option>';
    courseSelect.disabled = true;
    sessionSelect.disabled = true;

    if (departmentId) {
        fetch(`/get-courses/${departmentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    data.forEach(course => {
                        const option = document.createElement('option');
                        option.value = course.id;
                        option.textContent = course.course_name;
                        courseSelect.appendChild(option);
                    });
                    courseSelect.disabled = false;
                } else {
                    courseSelect.innerHTML = '<option value="">❌ لا توجد كورسات متاحة</option>';
                }
            })
            .catch(error => {
                console.error('❌ Error fetching courses:', error);
                courseSelect.innerHTML = '<option value="">⚠️ حدث خطأ، حاول مرة أخرى</option>';
            });
    }
});

document.getElementById('course_id').addEventListener('change', function () {
    const courseId = this.value;
    const sessionSelect = document.getElementById('course_session_id');

    sessionSelect.innerHTML = '<option value="">-- اختر الجلسة --</option>';
    sessionSelect.disabled = true;

    if (courseId) {
        fetch(`/get-sessions/${courseId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    data.forEach(session => {
                        const option = document.createElement('option');
                        option.value = session.id;
                        option.textContent = `${session.start_date} - ${session.end_date} (${session.start_time} - ${session.end_time})`;
                        sessionSelect.appendChild(option);
                    });
                    sessionSelect.disabled = false;
                } else {
                    sessionSelect.innerHTML = '<option value="">❌ لا توجد جلسات متاحة لهذا الكورس</option>';
                    sessionSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('❌ Error fetching sessions:', error);
                sessionSelect.innerHTML = '<option value="">⚠️ حدث خطأ، حاول مرة أخرى</option>';
            });
    }
});

document.getElementById('course_id').addEventListener('change', function () {
    const studyTimeContainer = document.getElementById('study_time_container');
    if (this.value) {
        studyTimeContainer.style.display = 'block';
    } else {
        studyTimeContainer.style.display = 'none';
    }
});

document.getElementById('course_session_id').addEventListener('change', function () {
    const studyTimeContainer = document.getElementById('study_time_container');
    if (this.value) {
        studyTimeContainer.style.display = 'none';
    }
});

// Add Multiple Phone Numbers
document.getElementById('add-phone').addEventListener('click', function () {
    let container = document.getElementById('phone-container');
    let newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.name = 'phones[]';
    newInput.classList.add('form-control', 'mt-2');
    newInput.placeholder = 'أدخل رقم هاتف آخر';
    container.appendChild(newInput);
});


</script>

@endsection
