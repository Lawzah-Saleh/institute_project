@extends('admin.layouts.app')

@section('title', 'تعديل القسم')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تعديل القسم</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">الأقسام</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('departments.update', $department->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>تفاصيل القسم</span></h5>
                                </div>

                                <!-- رقم القسم -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>رقم القسم <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $department->id }}" disabled>
                                    </div>
                                </div>

                                <!-- اسم القسم -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>اسم القسم <span class="login-danger">*</span></label>
                                        <input type="text" name="department_name" class="form-control" value="{{ $department->department_name }}" required>
                                    </div>
                                </div>

                                <!-- تفاصيل القسم -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>تفاصيل القسم <span class="login-danger">*</span></label>
                                        <input type="text" name="department_info" class="form-control" value="{{ $department->department_info }}" required>
                                    </div>
                                </div>

                                <!-- حالة القسم -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>حالة القسم <span class="login-danger">*</span></label>
                                        <select name="state" class="form-control" required>
                                            <option value="1" {{ $department->state ? 'selected' : '' }}>نشط</option>
                                            <option value="0" {{ !$department->state ? 'selected' : '' }}>غير نشط</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- زر التعديل -->
                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">تعديل</button>
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
