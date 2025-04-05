@extends('admin.layouts.app')

@section('title', 'تعديل جهة السداد')

@section('content')
<div class="page-wrapper" style="margin-right: 100px;padding:50px 20px;width: calc(105% - 150px);background:#f7f7fa;">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تعديل جهة السداد</h3>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('payment_sources.update', $paymentSource->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="name">اسم الجهة</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $paymentSource->name) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="status">الحالة</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="active" {{ $paymentSource->status == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ $paymentSource->status == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>

                    <div class="form-group text-end">
                        <button type="submit" class="btn btn-success">تحديث</button>
                        <a href="{{ route('payment_sources.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
