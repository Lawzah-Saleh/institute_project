@extends('admin.layouts.app')

@section('title', 'إضافة إجازة')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">إضافة إجازة جديدة</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('holidays.index') }}">الإجازات</a></li>
                        <li class="breadcrumb-item active">إضافة إجازة</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <form action="{{ route('holidays.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">اسم الإجازة</label>
                                <input type="text" name="name" class="form-control" placeholder="أدخل اسم الإجازة" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">تاريخ الإجازة</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">حالة الإجازة</label>
                                <select name="state" class="form-control">
                                    <option value="1" selected>مفعلة</option>
                                    <option value="0">غير مفعلة</option>
                                </select>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-success">حفظ</button>
                                <a href="{{ route('holidays.index') }}" class="btn btn-secondary">إلغاء</a>
                            </div>

                        </form>

                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div> <!-- col-md-12 -->
        </div> <!-- row -->

    </div> <!-- content -->
</div> <!-- page-wrapper -->

@endsection
