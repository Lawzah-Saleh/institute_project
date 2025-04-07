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

        <div class="card shadow-sm">
            <div class="card-body">
                {{-- عرض الأخطاء --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>⚠️ {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- نموذج التسجيل --}}
                <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        {{-- الاسم بالعربية --}}
                        <div class="col-md-6 mb-3">
                            <label>الاسم بالعربية *</label>
                            <input type="text" name="student_name_ar" value="{{ old('student_name_ar') }}" class="form-control" required>
                        </div>

                        {{-- الاسم بالإنجليزية --}}
                        <div class="col-md-6 mb-3">
                            <label>الاسم بالإنجليزية *</label>
                            <input type="text" name="student_name_en" value="{{ old('student_name_en') }}" class="form-control" required>
                        </div>

                        {{-- أرقام الهواتف --}}
                        <div class="col-md-6 mb-3">
                            <label>رقم الهاتف *</label>
                            <div id="phone-container">
                                <input type="text" name="phones[]" value="{{ old('phones.0') }}" class="form-control mb-1" required>
                            </div>
                            <button type="button" id="add-phone" class="btn btn-sm btn-outline-secondary">+ رقم إضافي</button>
                        </div>

                        {{-- الجنس --}}
                        <div class="col-md-6 mb-3">
                            <label>الجنس *</label>
                            <select name="gender" value="{{ old('gender') }}" class="form-select" required>
                                <option value="">اختر</option>
                                <option value="male">ذكر</option>
                                <option value="female">أنثى</option>
                            </select>
                        </div>

                        {{-- المؤهل --}}
                        <div class="col-md-6 mb-3">
                            <label>المؤهل العلمي *</label>
                            <input type="text" name="qualification" value="{{ old('qualification') }}" class="form-control" required>
                        </div>

                        {{-- الميلاد --}}
                        <div class="col-md-6 mb-3">
                            <label>تاريخ الميلاد *</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>مكان الميلاد *</label>
                            <input type="text" name="birth_place" value="{{ old('birth_place') }}" class="form-control" required>
                        </div>

                        {{-- العنوان والبريد --}}
                        <div class="col-md-6 mb-3">
                            <label>العنوان *</label>
                            <input type="text" name="address" value="{{ old('address') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                        </div>

                        {{-- القسم والكورس --}}
                        <div class="col-md-6 mb-3">
                            <label>القسم *</label>
                            <select name="department" id="department" class="form-select"  required>
                                <option value="">اختر القسم</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>الكورس *</label>
                            <select name="course_id" id="courses" class="form-select" required>
                                <option value="">اختر الكورس</option>
                            </select>
                        </div>

                        {{-- السعر --}}
                        <div class="col-md-6 mb-3">
                            <label>سعر الكورس</label>
                            <input type="number" id="course_price" class="form-control" readonly>
                        </div>

                        {{-- وقت الدراسة --}}
                        <div class="col-md-6 mb-3" id="study_time_container" style="display:none;">
                            <label>وقت الدراسة</label>
                            <select name="study_time" class="form-select">
                                <option value="8-10">8:00 - 10:00</option>
                                <option value="10-12">10:00 - 12:00</option>
                                <option value="12-2">12:00 - 2:00</option>
                                <option value="2-4">2:00 - 4:00</option>
                                <option value="4-6">4:00 - 6:00</option>
                            </select>
                        </div>

                        {{-- الجلسة --}}
                        <div class="col-md-6 mb-3">
                            <label>الجلسة (اختياري)</label>
                            <select name="course_session_id" id="course_session_id" class="form-select" disabled>
                                <option value="">-- اختر الجلسة --</option>
                            </select>
                        </div>

                        {{-- الدفع --}}
                        <div class="col-md-6 mb-3">
                            <label>المبلغ المدفوع *</label>
                            <input type="number" name="amount_paid" class="form-control" value="{{ old('amount_paid') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>طريقة الدفع *</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">اختر طريقة الدفع</option>
                                @foreach($paymentSources as $source)
                                    <option value="{{ $source->name }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        {{-- الحالة --}}
                        <div class="col-md-6 mb-3">
                            <label>الحالة *</label>
                            <select name="state" class="form-select" required>
                                <option value="1">نشط</option>
                                <option value="0">غير نشط</option>
                            </select>
                        </div>

                        {{-- الصورة --}}
                        <div class="col-md-6 mb-3">
                            <label>صورة الطالب</label>
                            <input type="file" name="image" class="form-control" value="{{ old('image') }}" accept="image/*">
                        </div>

                        {{-- زر الحفظ --}}
                        <div class="col-12">
                            <button type="submit" class="btn "style="background-color: #196098; color: white;">💾 حفظ الطالب</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- سكربتات --}}
<script>
    document.getElementById('department').addEventListener('change', function () {
        let departmentId = this.value;
        let courseSelect = document.getElementById('courses');
        courseSelect.innerHTML = '<option>جاري التحميل...</option>';

        fetch(`/get-courses/${departmentId}`)
            .then(res => res.json())
            .then(data => {
                courseSelect.innerHTML = '<option>اختر دورة</option>';
                data.forEach(course => {
                    let option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = course.course_name;
                    courseSelect.appendChild(option);
                });
            });
    });

    document.getElementById('courses').addEventListener('change', function () {
        let courseId = this.value;
        fetch(`/get-course-price/${courseId}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('course_price').value = data.price || 0;
            });

        fetch(`/get-sessions/${courseId}`)
            .then(res => res.json())
            .then(data => {
                let sessionSelect = document.getElementById('course_session_id');
                sessionSelect.innerHTML = '<option value="">-- اختر الجلسة --</option>';
                sessionSelect.disabled = data.length === 0;
                data.forEach(session => {
                    let opt = document.createElement('option');
                    opt.value = session.id;
                    opt.textContent = `${session.start_date} إلى ${session.end_date}`;
                    sessionSelect.appendChild(opt);
                });
            });

        document.getElementById('study_time_container').style.display = 'block';
    });

    document.getElementById('add-phone').addEventListener('click', function () {
        let container = document.getElementById('phone-container');
        let input = document.createElement('input');
        input.type = 'text';
        input.name = 'phones[]';
        input.className = 'form-control mt-2';
        input.placeholder = 'رقم هاتف إضافي';
        container.appendChild(input);
    });

    document.getElementById('course_session_id').addEventListener('change', function () {
        let timeContainer = document.getElementById('study_time_container');
        timeContainer.style.display = this.value ? 'none' : 'block';
    });
</script>

@endsection
