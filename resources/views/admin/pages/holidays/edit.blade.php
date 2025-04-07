@extends('admin.layouts.app')

@section('title', 'تعديل الإجازة')

@section('content')

<div class="page-wrapper" style="background-color: #F9F9FB;">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تعديل الإجازة</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('holidays.index') }}">الإجازات</a></li>
                        <li class="breadcrumb-item active">تعديل الإجازة</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <form action="{{ route('holidays.update', $holiday->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">اسم الإجازة</label>
                                <input type="text" name="name" class="form-control" value="{{ $holiday->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">تاريخ الإجازة</label>
                                <input type="date" name="date" class="form-control" value="{{ $holiday->date }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">حالة الإجازة</label>
                                <select name="state" class="form-control">
                                    <option value="1" {{ $holiday->state ? 'selected' : '' }}>مفعلة</option>
                                    <option value="0" {{ !$holiday->state ? 'selected' : '' }}>غير مفعلة</option>
                                </select>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-success">تحديث</button>
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
