@extends('admin.layouts.app')

@section('title', 'إضافة إعلان جديد')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">إضافة إعلان جديد</h3>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('advertisements.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>عنوان الإعلان <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="أدخل عنوان الإعلان" value="{{ old('title') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>تاريخ الانتهاء <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>محتوى الإعلان <span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control" rows="4" placeholder="أدخل تفاصيل الإعلان" required>{{ old('content') }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>صورة الإعلان</label>
                            <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                            <img id="imagePreview" class="mt-3" src="#" style="max-width: 150px; display: none;">
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary px-5">إضافة</button>
                        <a href="{{ route('advertisements.index') }}" class="btn btn-danger px-5">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<style>
    body {
        background-color: #f7f7fa;
        direction: rtl;
        text-align: right;
    }

    .btn-primary {
        background-color: #196098;
        border-color: #196098;
    }
    .btn-primary:hover {
        background-color: #154a7a;
    }
    .btn-danger {
        background-color: #e94c21;
        border-color: #e94c21;
    }
    .btn-danger:hover {
        background-color: #d1401a;
    }
</style>

<script>
    function previewImage(event) {
        const image = document.getElementById('imagePreview');
        image.src = URL.createObjectURL(event.target.files[0]);
        image.style.display = 'block';
    }
</script>

@endsection
