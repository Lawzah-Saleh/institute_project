@extends('admin.layouts.app')

@section('title', 'إضافة موظف')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">إضافة موظف</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('employees') }}">الموظفون</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Add Employee Form -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
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

                        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <!-- Basic Details -->
                                <div class="col-12">
                                    <h5 class="form-title"><span>التفاصيل الأساسية</span></h5>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>الاسم باللغة الإنجليزية <span class="text-danger">*</span></label>
                                        <input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>الاسم باللغة العربية <span class="text-danger">*</span></label>
                                        <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar') }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>رقم الهاتف <span class="text-danger">*</span></label>
                                        <input type="text" name="phones" class="form-control" value="{{ old('phone') }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>العنوان <span class="text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>الجنس <span class="text-danger">*</span></label>
                                        <select class="form-control select" name="gender" required>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>البريد الإلكتروني <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>كلمة المرور <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control" placeholder="أدخل كلمة المرور (أو استخدم كلمة مرور تلقائية)">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>تاريخ الميلاد <span class="text-danger">*</span></label>
                                        <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>مكان الميلاد <span class="text-danger">*</span></label>
                                        <select name="birth_place" class="form-control" required>
                                            <option value="">-- اختر المحافظة --</option>
                                            @foreach (['صنعاء', 'عدن', 'تعز', 'الحديدة', 'إب', 'المكلا', 'مارب', 'شبوة', 'حضرموت', 'البيضاء', 'صعدة'] as $place)
                                                <option value="{{ $place }}" {{ old('birth_place') == $place ? 'selected' : '' }}>{{ $place }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <!-- Role Selection -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>الدور الوظيفي <span class="text-danger">*</span></label>
                                        <select name="role_id" class="form-control" required>
                                            <option value="">-- اختر الدور الوظيفي --</option>
                                            @foreach (App\Models\Role::all() as $role)
                                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Employment Type -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>نوع الوظيفة <span class="text-danger">*</span></label>
                                        <input type="text" name="emptype" class="form-control" value="{{ old('emptype') }}" required>
                                    </div>
                                </div>

                                <!-- Employment Status -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>حالة الموظف <span class="text-danger">*</span></label>
                                        <select name="state" class="form-control" required>
                                            <option value="1" {{ old('state') == '1' ? 'selected' : '' }}>نشط</option>
                                            <option value="0" {{ old('state') == '0' ? 'selected' : '' }}>غير نشط</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>صورة الموظف <span class="text-danger">*</span></label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                    </div>
                                </div>

                                <!-- Qualifications -->
                                <div class="col-12">
                                    <h5 class="form-title"><span>المؤهلات العلمية</span></h5>
                                </div>

                                <div id="qualification-fields">
                                    <div class="row qualification-item">
                                        <div class="col-md-4 mb-3">
                                            <label>المؤهل العلمي</label>
                                            <input type="text" name="qualification_name[]" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label>الجهة المانحة</label>
                                            <input type="text" name="issuing_authority[]" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                                <label>تاريخ المنح <span class="text-danger">*</span></label>
                                                <input type="date" name="obtained_date[]" class="form-control"        value="{{ old('obtained_date.0') ?? '' }}">

                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label>ملف الشهادة</label>
                                            <input type="file" name="certification[]" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-info" id="add-qualification"style="background-color: #007BFF">إضافة مؤهل آخر</button>

                                <!-- Submit Button -->
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary" >إضافة الموظف</button>
                                </div>

                            </div> <!-- End Row -->
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.getElementById('add-qualification').addEventListener('click', function() {
    let qualificationFields = document.getElementById('qualification-fields');
    let newField = qualificationFields.firstElementChild.cloneNode(true);
    qualificationFields.appendChild(newField);
});
</script>

@endsection
