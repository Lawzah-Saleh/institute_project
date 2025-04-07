@extends('admin.layouts.app')

@section('title', 'تعديل بيانات الطالب')

@section('content')
<div class="page-wrapper" style="background-color: #F9F9FB;">
    <div class="content container-fluid">

        <!-- 🔹 عنوان الصفحة -->
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <h3 class="page-title">تعديل بيانات الطالب</h3>
            <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> الرجوع
            </a>
        </div>

        <!-- 🔹 اختيار القسم الذي تريد تعديله -->
        <div class="text-center mb-4">
            <a href="{{ route('students.edit', ['id' => $student->id, 'section' => 'personal']) }}" class="btn  mx-2" style="background-color: #e94c21;color: white">
                 تعديل البيانات الشخصية
            </a>
            <a href="{{ route('students.edit', ['id' => $student->id, 'section' => 'academic']) }}" class="btn  mx-2" style="background-color: #e94c21;color: white">
                 تعديل بيانات الكورس والجلسة
            </a>
            <a href="{{ route('students.edit', ['id' => $student->id, 'section' => 'financial']) }}" class="btn  mx-2" style="background-color: #e94c21;color: white">
                 تعديل البيانات المالية
            </a>
        </div>

        <!-- 🔹 نموذج التعديل -->
        <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card shadow-sm">
                <div class="card-body">



                    <!-- 🔸 تعديل البيانات الشخصية -->
                    @if ($section == 'personal' || $section == 'all')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>الاسم بالعربية *</label>
                            <input type="text" name="student_name_ar" class="form-control" value="{{ old('student_name_ar', $student->student_name_ar) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>الاسم بالإنجليزية *</label>
                            <input type="text" name="student_name_en" class="form-control" value="{{ old('student_name_en', $student->student_name_en) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>رقم الهاتف *</label>
                            <div id="phone-container">
                                @php $phones = json_decode($student->phones, true) ?? []; @endphp
                                @foreach($phones as $phone)
                                    <input type="text" name="phones[]" class="form-control mt-2" value="{{ $phone }}">
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary mt-2" id="add-phone">+ رقم إضافي</button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $student->email) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>المؤهل العلمي *</label>
                            <input type="text" name="qualification" class="form-control" value="{{ old('qualification', $student->qualification) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>الجنس *</label>
                            <select name="gender" class="form-select">
                                <option value="male" {{ $student->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                                <option value="female" {{ $student->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>تاريخ الميلاد *</label>
                            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $student->birth_date) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>مكان الميلاد *</label>
                            <input type="text" name="birth_place" class="form-control" value="{{ old('birth_place', $student->birth_place) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>العنوان *</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $student->address) }}" required>
                        </div>
                    </div>
                    @endif
                                        <!-- 🔸 تعديل الصورة الشخصية (إذا كانت البيانات الشخصية مختارة) -->
                                        @if ($section == 'personal' || $section == 'all')
                                        <div class="row mb-4">
                                            <div class="col-md-3 text-center">
                                                <img src="{{ $student->image ? asset('storage/' . $student->image) : asset('images/default-student.png') }}"
                                                     class="rounded-circle img-thumbnail" style="width: 150px; height: 150px;" alt="صورة الطالب">
                                                <input type="file" name="image" class="form-control mt-3">
                                            </div>
                                        </div>
                                        @endif

                    <!-- 🔸 تعديل بيانات الكورس والجلسة (إذا كانت البيانات الأكاديمية مختارة) -->
                    @if ($section == 'academic' || $section == 'all')
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <label>القسم *</label>
                            <select name="department_id" id="department_id" class="form-select" required >
                                <option value="">-- اختر القسم --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ optional($student->courses->first())->department_id == $department->id ? 'selected' : '' }}>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>الكورس</label>
                            <select name="course_id" id="course_id" class="form-select">
                                <option value="">-- اختر الكورس --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ optional($student->courses->first())->id == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>الجلسة</label>
                            <select name="course_session_id" id="session_id" class="form-select">
                                <option value="">-- اختر الجلسة --</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ optional($student->sessions->first())->id == $session->id ? 'selected' : '' }}>
                                        {{ $session->start_date }} - {{ $session->end_date }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <!-- 🔸 تعديل البيانات المالية (إذا كانت البيانات المالية مختارة) -->
                    @if ($section == 'financial' || $section == 'all')
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <label>المبلغ الإجمالي *</label>
                            <input type="number" name="total_amount" class="form-control" value="{{ old('total_amount', optional($student->payments->first())->total_amount) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>المبلغ المدفوع *</label>
                            <input type="number" name="amount_paid" class="form-control" value="{{ old('amount', optional($student->payments->first())->amount_paid) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>طريقة الدفع *</label>
                            <select name="payment_source_id" class="form-select" required>
                                <option value="">-- اختر طريقة الدفع --</option>
                                @foreach($paymentSources as $source)
                                    <option value="{{ $source->id }}" {{ optional($student->payments->first())->payment_source_id == $source->id ? 'selected' : '' }}>
                                        {{ $source->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <!-- 🔸 أزرار التعديل والحفظ -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn " style="background-color: #196098; color: #fff;"><i class="fas fa-save"></i> حفظ التعديلات</button>
                        <a href="{{ route('students.index') }}" class="btn "style="background-color: #e94c21; color: #fff;"><i class="fas fa-times"></i> إلغاء</a>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // سكربت إضافة رقم هاتف إضافي
    document.getElementById('add-phone').addEventListener('click', function () {
        let input = document.createElement('input');
        input.type = 'text';
        input.name = 'phones[]';
        input.classList.add('form-control', 'mt-2');
        input.placeholder = 'رقم إضافي';
        document.getElementById('phone-container').appendChild(input);
    });
</script>

@endsection
