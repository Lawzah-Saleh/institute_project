@extends('admin.layouts.app')

@section('title', 'إضافة جهة سداد جديدة')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">إضافة جهة سداد جديدة</h3>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('payment_sources.store') }}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">اسم الجهة</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="status">الحالة</label>
                        <select name="status" class="form-control" id="status" required>
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">حفظ</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
