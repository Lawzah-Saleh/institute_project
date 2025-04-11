@extends('Teacher-dashboard.layouts.app')

@section('title', 'تعديل بيانات الموظف')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid" style="background-color: #f9f9fb;">

          {{-- الترحيب --}}
          <div class="flex justify-center mb-4">
            <div class="bg-white shadow-md rounded-lg p-4 text-center w-full md:w-2/3">
                <h3 class="text-2xl font-bold text-gray-700">مرحبًا أستاذ {{ $employee->name_ar }}</h3>
            </div>
        </div>


        <!-- رأس الصفحة -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col d-flex justify-content-between align-items-center">
                    <h3 class="page-title m-0">تعديل البيانات</h3>
                    <a href="{{ url('T-profile') }}"
                        class="btn text-white"
                        style="background-color: #0b4c8a; transition: all 0.3s ease; border-radius: 6px;"
                        onmouseover="this.style.backgroundColor='#0d5ca6'; this.style.transform='scale(1.05)'"
                        onmouseout="this.style.backgroundColor='#0b4c8a'; this.style.transform='scale(1)'">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- فورم التعديل -->
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="card shadow-sm">
                    <div class="card-body">


                        <form method="POST" action="{{ route('teacher.update-profile') }}" enctype="multipart/form-data">
                            @csrf
                            @method('POST')


                            <div class="row">

                                <!-- Basic Details -->
                                <div class="col-12">
                                    <h5 class="form-title"><span>التفاصيل الأساسية</span></h5>
                                </div>

                            <div class="col-12 col-sm-4">

                                <div class="form-group local-forms">
                                    <label>الاسم باللغة الإنجليزية </label>
                                    <input type="text" name="name_en" class="form-control" value="{{ $employee->name_en }}" required>
                                </div>
                            </div>



                                {{-- <div class="col-md-4">
                                    <label class="form-label">الاسم باللغة الإنجليزية</label>
                                    <input type="text" name="name_en" class="form-control" value="{{ $employee->name_en }}">
                                </div> --}}

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                    <label class="form-label">الاسم باللغة العربية</label>
                                    <input type="text" name="name_ar" class="form-control" value="{{ $employee->name_ar }}"required>
                                </div>
                            </div>



                            <div class="col-12 col-sm-4">
                                <div class="form-group local-forms">
                                    <label class="form-label">رقم الهاتف</label>
                                    <input type="text" name="phones" class="form-control" value="{{ $employee->phones }}"required>
                                </div>
                            </div>


                            <div class="col-12 col-sm-4">
                                <div class="form-group local-forms">
                                    <label class="form-label">العنوان</label>
                                    <input type="text" name="address" class="form-control" value="{{ $employee->address }}"required>>
                                </div>
                            </div>



                            <div class="col-12 col-sm-4">
                                <div class="form-group local-forms">
                                    <label>الجنس <span class="text-danger">*</span></label>
                                    <select class="form-control select" name="gender" required>
                                        <option value="male" {{ $employee->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                                        <option value="female" {{ $employee->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                                    </select>
                                </div>
                            </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <input type="email" name="email" class="form-control" value="{{ $employee->email }}"required>
                                </div>
                            </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                    <label class="form-label">تاريخ الميلاد</label>
                                    <input type="date" name="birth_date" class="form-control" value="{{ $employee->birth_date }}"required>
                                </div>
                            </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                    <label class="form-label">مكان الميلاد</label>
                                    <input type="text" name="birth_place" class="form-control" value="{{ $employee->birth_place }}"required>
                                </div>
                            </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">

                                    <label class="form-label">صورة البروفايل</label>
                                    <div class="mb-2 image-upload-preview">
                                        <img id="employeeImagePreview"
                                            src="{{ $employee->image && file_exists(public_path('storage/' . $employee->image))
                                                    ? asset('storage/' . $employee->image)
                                                    : asset('Teacher/assets/img/profiles/profile-t.png') }}"
                                            alt="صورة الموظف"
                                            class="img-thumbnail rounded-circle">
                                    </div>
                                    <input type="file" class="form-control" name="image" accept="image/*" onchange="previewImage(event)">
                                </div>
                            </div>



                                <div class="col-12 text-end">
                                    <button type="submit" class="btn text-white" style="background-color: #0b4c8a;">
                                        <i class="fas fa-save me-1"></i> حفظ التعديلات
                                    </button>
                                </div>

                        </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .image-upload-preview img {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border: 4px solid #0b4c8a;
        background-color: white;
        padding: 4px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('employeeImagePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
