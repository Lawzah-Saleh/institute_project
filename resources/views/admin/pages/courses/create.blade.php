@extends('admin.layouts.app')

@section('title', 'إضافة دورة')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid" style="background-color: #F9F9FB;">

        <!-- رسالة نجاح -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- عرض الأخطاء -->
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">إضافة دورة</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">الدورات</a></li>
                        <li class="breadcrumb-item active">إضافة دورة</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('courses.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>تفاصيل الدورة</span></h5>
                                </div>

                                <!-- اسم الدورة -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>اسم الدورة <span class="login-danger">*</span></label>
                                        <input type="text" name="course_name" class="form-control" placeholder="ادخل اسم الدورة" value="{{ old('course_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>وصف الدورة <span class="login-danger">*</span></label>
                                        <input type="text" name="description" class="form-control" placeholder="ادخل وصف الدورة" value="{{ old('description') }}" required>
                                    </div>
                                </div>

                                <!-- مدة الدورة -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>مدة الدورة (اختياري)</label>
                                        <input type="number" name="duration" class="form-control" placeholder="ادخل مدة الدورة" value="{{ old('duration') }}">
                                    </div>
                                </div>


                                <!-- اختيار القسم -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>القسم <span class="login-danger">*</span></label>
                                        <select name="department_id" class="form-control" required>
                                            <option value="">اختر القسم</option>
                                            @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->department_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- الحالة -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>الحالة <span class="login-danger">*</span></label>
                                        <select name="state" class="form-control" required>
                                            <option value="1" {{ old('state') == '1' ? 'selected' : '' }}>نشط</option>
                                            <option value="0" {{ old('state') == '0' ? 'selected' : '' }}>غير نشط</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- زر الإضافة -->
                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">إضافة</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
