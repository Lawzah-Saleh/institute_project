@extends('admin.layouts.app')

@section('title', 'إضافة جهة سداد جديدة')

@section('content')
<div class="page-wrapper" style="background-color: #F9F9FB;">
    <div class="content container-fluid">
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">إضافة جهة سداد جديدة</h3>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('payment_sources.store') }}">
            @csrf
            <div class="card shadow-sm">
                <div class="card-body">

                    <!-- اسم الجهة -->
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">اسم الجهة <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>

                    <!-- حالة الجهة -->
                    <div class="form-group mb-3">
                        <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" id="status" required>
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                        </select>
                    </div>

                    <!-- أزرار حفظ وإلغاء -->
                    <div class="form-group text-end">
                        <button type="submit" class="btn  mt-4 px-4 py-2"style="background-color: #196098;color: white;">
                            حفظ
                        </button>
                        <a href="{{ route('payment_sources.index') }}" class="btn  mt-4 px-4 py-2"style="background-color: #e94c21;color: white;">
                            إلغاء
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
