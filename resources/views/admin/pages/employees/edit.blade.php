@extends('admin.layouts.app')

@section('title', 'تعديل الموظف')

@section('content')

<div class="page-wrapper">
<div class="content container-fluid">

    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">تعديل الموظف</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('employees') }}">الموظفون</a></li>
                    <li class="breadcrumb-item active">تعديل الموظف</li>
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

                    <form action="{{ url('employees/'.$employee->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
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
                                    <input type="text" name="phone" class="form-control" value="{{ $employee->phone }}" required>
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
                                    <select class="form-control" name="gender" required>
                                        <option value="Male" {{ $employee->gender == 'Male' ? 'selected' : '' }}>ذكر</option>
                                        <option value="Female" {{ $employee->gender == 'Female' ? 'selected' : '' }}>أنثى</option>
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
                                    <input type="date" name="Day_birth" class="form-control" value="{{ $employee->Day_birth }}" required>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="form-group local-forms">
                                    <label>مكان الميلاد <span class="text-danger">*</span></label>
                                    <select name="place_birth" class="form-control" required>
                                        <option value="">{{ $employee->place_birth }}</option>
                                        @foreach (['صنعاء', 'عدن', 'تعز', 'الحديدة', 'إب', 'المكلا', 'مارب', 'شبوة', 'حضرموت', 'البيضاء', 'صعدة'] as $place)
                                            <option value="{{ $place }}" {{ $employee->place_birth == $place ? 'selected' : '' }}>{{ $place }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Job Type -->
                            <div class="col-12 col-sm-4">
                                <div class="form-group local-forms">
                                    <label>نوع الوظيفة <span class="text-danger">*</span></label>
                                    <select name="emptype" class="form-control" required>
                                        <option value="teacher" {{ $employee->emptype == 'teacher' ? 'selected' : '' }}>معلم</option>
                                        <option value="employee" {{ $employee->emptype == 'employee' ? 'selected' : '' }}>موظف عادي</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="form-group local-forms">
                                    <label>حالة الموظف <span class="text-danger">*</span></label>
                                    <select name="state" class="form-control" required>
                                        <option value="نشط" {{ $employee->state == 'نشط' ? 'selected' : '' }}>نشط</option>
                                        <option value="غير نشط" {{ $employee->state == 'غير نشط' ? 'selected' : '' }}>غير نشط</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="form-group local-forms">
                                    <label>صورة الموظف</label>
                                    <input type="file" name="image" class="form-control">
                                    @if($employee->image)
                                        <img src="{{ asset('storage/'.$employee->image) }}" width="100" alt="Employee Image">
                                    @endif
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <div class="student-submit">
                                    <button type="submit" class="btn btn-primary">تحديث البيانات</button>
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
