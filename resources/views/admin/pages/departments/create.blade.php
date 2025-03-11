@extends('admin.layouts.app')

@section('title', 'أضافة قسم')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid" style="background-color: #F9F9FB;">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">أضافة قسم</h3>
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
                        <form action="{{ route('departments.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>تفاصيل القسم</span></h5>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>اسم القسم <span class="login-danger">*</span></label>
                                        <input type="text" name="department_name" class="form-control" placeholder="أدخل اسم القسم" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>تفاصيل القسم <span class="login-danger">*</span></label>
                                        <input type="text" name="department_info" class="form-control" placeholder="أدخل تفاصيل القسم" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>حالة القسم <span class="login-danger">*</span></label>
                                        <select name="state" class="form-control" required>
                                            <option value="1">نشط</option>
                                            <option value="0">غير نشط</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">أضافة</button>
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
