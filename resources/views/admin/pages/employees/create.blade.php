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
                        <li class="breadcrumb-item active">إضافة موظف</li>
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
                                        <input type="text" name="name_en" class="form-control" placeholder="الاسم بالإنجليزية" value="{{ old('name_en') }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>الاسم باللغة العربية <span class="text-danger">*</span></label>
                                        <input type="text" name="name_ar" class="form-control" placeholder="الاسم بالعربية" value="{{ old('name_ar') }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>رقم الهاتف <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" placeholder="أدخل رقم الهاتف" value="{{ old('phone') }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>العنوان <span class="text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control" placeholder="أدخل العنوان" value="{{ old('address') }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>الجنس <span class="text-danger">*</span></label>
                                        <select class="form-control select" name="gender" required>
                                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>ذكر</option>
                                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>أنثى</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>البريد الإلكتروني <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" placeholder="أدخل البريد الإلكتروني" value="{{ old('email') }}" required>
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
                                        <input type="date" name="Day_birth" class="form-control" value="{{ old('Day_birth') }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>مكان الميلاد <span class="text-danger">*</span></label>
                                        <select name="place_birth" class="form-control" required>
                                            <option value="">-- اختر المحافظة --</option>
                                            @foreach (['صنعاء', 'عدن', 'تعز', 'الحديدة', 'إب', 'المكلا', 'مارب', 'شبوة', 'حضرموت', 'البيضاء', 'صعدة'] as $place)
                                                <option value="{{ $place }}" {{ old('place_birth') == $place ? 'selected' : '' }}>{{ $place }}</option>
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
                                        <input type="text" name="emptype" class="form-control" placeholder="أدخل نوع الوظيفة" value="{{ old('emptype') }}" required>
                                    </div>
                                </div>

                                <!-- Employment Status -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>حالة الموظف <span class="text-danger">*</span></label>
                                        <select name="state" class="form-control" required>
                                            <option value="نشط" {{ old('state') == 'نشط' ? 'selected' : '' }}>نشط</option>
                                            <option value="غير نشط" {{ old('state') == 'غير نشط' ? 'selected' : '' }}>غير نشط</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>صورة الموظف <span class="text-danger">*</span></label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">إضافة الموظف</button>
                                    </div>
                                </div>

                            </div> <!-- End Row -->
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
