@extends('admin.layouts.app')

@section('title', 'تعديل بيانات الموظف')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تعديل بيانات الموظف</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('employees') }}">الموظفين</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Edit Employee Form -->
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

                        <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">

                                <!-- Basic Details -->
                                <div class="col-12">
                                    <h5 class="form-title"><span>التفاصيل الأساسية</span></h5>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>الاسم باللغة الإنجليزية <span class="text-danger">*</span></label>
                                        <input type="text" name="name_en" class="form-control" value="{{ $employee->name_en }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>الاسم باللغة العربية <span class="text-danger">*</span></label>
                                        <input type="text" name="name_ar" class="form-control" value="{{ $employee->name_ar }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>رقم الهاتف <span class="text-danger">*</span></label>
                                        <input type="text" name="phones" class="form-control" value="{{ $employee->phones }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>العنوان <span class="text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control" value="{{ $employee->address }}" required>
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
                                        <label>البريد الإلكتروني <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="{{ $employee->email }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>تاريخ الميلاد <span class="text-danger">*</span></label>
                                        <input type="date" name="birth_date" class="form-control" value="{{ $employee->birth_date }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>مكان الميلاد <span class="text-danger">*</span></label>
                                        <input type="text" name="birth_place" class="form-control" value="{{ $employee->birth_place }}" required>
                                    </div>
                                </div>

                                <!-- Role Selection -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>الدور الوظيفي <span class="text-danger">*</span></label>
                                        <select name="role_id" class="form-control" required>
                                            @foreach (App\Models\Role::all() as $role)
                                                <option value="{{ $role->id }}" {{ $employee->role_id == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>العنوان <span class="text-danger">*</span></label>
                                        <input type="text" name="emptype" class="form-control" value="{{ $employee->emptype }}" required>
                                    </div>
                                </div>
                                <!-- Employment Status -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>حالة الموظف <span class="text-danger">*</span></label>
                                        <select name="state" class="form-control" required>
                                            <option value="1" {{ $employee->state ? 'selected' : '' }}>نشط</option>
                                            <option value="0" {{ !$employee->state ? 'selected' : '' }}>غير نشط</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- صورة الموظف -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>صورة الموظف</label>
                                        <input type="file" name="image" class="form-control">
                                        @if($employee->image)
                                            <img src="{{ asset('storage/' . $employee->image) }}" width="80" class="mt-2">
                                        @endif
                                    </div>
                                </div>

                                <!-- المؤهلات العلمية -->
                                <div class="col-12">
                                    <h5 class="form-title"><span>المؤهلات العلمية</span></h5>
                                </div>

                                <div id="qualification-fields">
                                    @foreach ($employee->qualifications as $qualification)
                                    <div class="row qualification-item">
                                        <div class="col-md-4 mb-3">
                                            <label>المؤهل العلمي</label>
                                            <input type="text" name="qualification_name[]" class="form-control" value="{{ $qualification->qualification_name }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label>الجهة المانحة</label>
                                            <input type="text" name="issuing_authority[]" class="form-control" value="{{ $qualification->issuing_authority }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label>تاريخ الحصول</label>
                                            <input type="date" name="obtained_date[]" class="form-control" value="{{ $qualification->obtained_date }}" required>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <button type="button" class="btn btn-primary" id="add-qualification">إضافة مؤهل آخر</button>

                                <!-- Submit Button -->
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
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
