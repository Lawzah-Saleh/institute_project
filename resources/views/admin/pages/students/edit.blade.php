@extends('admin.layouts.app')

@section('title', 'تعديل بيانات الطالب')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- 🔹 Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <h3 class="page-title">تعديل بيانات الطالب</h3>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> الرجوع
            </a>
        </div>

        <!-- 🔹 Student Form -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Student Image -->
                        <div class="col-md-3 text-center">
                            <img src="{{ $student->image ? asset('storage/' . $student->image) : asset('images/default-student.png') }}" 
                                 alt="صورة الطالب" 
                                 class="rounded-circle shadow-sm img-thumbnail" 
                                 style="width: 140px; height: 140px;">
                            <input type="file" name="image" class="form-control mt-3">
                        </div>

                        <!-- Student Details -->
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>الاسم بالعربية <span class="text-danger">*</span></label>
                                    <input type="text" name="student_name_ar" class="form-control" 
                                           value="{{ old('student_name_ar', $student->student_name_ar) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label>الاسم بالإنجليزية <span class="text-danger">*</span></label>
                                    <input type="text" name="student_name_en" class="form-control" 
                                           value="{{ old('student_name_en', $student->student_name_en) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label>رقم الهاتف <span class="text-danger">*</span></label>
                                    <div id="phone-container">
                                        @php
                                            $phones = json_decode($student->phones, true) ?? [];
                                        @endphp
                                
                                        @foreach($phones as $phone)
                                            <input type="text" name="phones[]" class="form-control mt-2" value="{{ old('phones.' . $loop->index, $phone) }}">
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-sm btn-secondary mt-2" id="add-phone">+ إضافة رقم آخر</button>
                                </div>
                                

                                <div class="col-md-6">
                                    <label>البريد الإلكتروني</label>
                                    <input type="email" name="email" class="form-control" 
                                           value="{{ old('email', $student->email) }}">
                                </div>

                                <div class="col-md-6">
                                    <label>المؤهل <span class="text-danger">*</span></label>
                                    <input type="text" name="qualification" class="form-control" 
                                           value="{{ old('qualification', $student->qualification) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label>الجنس <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-select">
                                        <option value="male" {{ $student->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                                        <option value="female" {{ $student->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>تاريخ الميلاد <span class="text-danger">*</span></label>
                                    <input type="date" name="birth_date" class="form-control" 
                                           value="{{ old('birth_date', $student->birth_date) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label>مكان الميلاد <span class="text-danger">*</span></label>
                                    <input type="text" name="birth_place" class="form-control" 
                                           value="{{ old('birth_place', $student->birth_place) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label>العنوان <span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control" 
                                           value="{{ old('address', $student->address) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label>الحالة <span class="text-danger">*</span></label>
                                    <select name="state" class="form-select">
                                        <option value="1" {{ $student->state ? 'selected' : '' }}>نشط</option>
                                        <option value="0" {{ !$student->state ? 'selected' : '' }}>غير نشط</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 🔹 Course & Session -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label>القسم <span class="text-danger">*</span></label>
                            <select name="department_id" id="department_id" class="form-select" required>
                                <option value="">-- اختر القسم --</option>
                                @foreach($departments as $department)
                                <option value="{{ $department->id }}" 
                                    {{ optional($student->courses->first())->department_id == $department->id ? 'selected' : '' }}>
                                                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>الكورس</label>
                            <select name="course_id" id="course_id" class="form-select">
                                <option value="">-- اختر الكورس --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ $student->courses->first()->id == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>الجلسة</label>
                            <select name="course_session_id" id="session_id" class="form-select">
                                <option value="">-- اختر الجلسة --</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ $student->sessions->first()->id == $session->id ? 'selected' : '' }}>
                                        {{ $session->start_date }} - {{ $session->end_date }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- 🔹 Submit Button -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ التعديلات</button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> إلغاء</a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<!-- 🔹 JavaScript لتحميل الكورسات والجلسات عند تغيير القسم -->
<script>
document.getElementById('department_id').addEventListener('change', function () {
    fetch(`/get-courses/${this.value}`)
        .then(response => response.json())
        .then(data => {
            const courseSelect = document.getElementById('course_id');
            courseSelect.innerHTML = '<option value="">-- اختر الكورس --</option>';
            data.forEach(course => {
                courseSelect.innerHTML += `<option value="${course.id}">${course.course_name}</option>`;
            });
        });
});
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
