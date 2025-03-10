@extends('admin.layouts.app')

@section('title', 'إضافة معلومات المعهد')

@section('content')

<div class="page-wrapper" style="background-color: #f7f7fa;">
    <div class="content container-fluid">
        <div style="font-size: large">إضافة معلومات المعهد </div>
        <div class="card-body">
            <form action="{{ route('institute.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>اسم المعهد <span class="text-danger">*</span></label>
                        <input type="text" name="institute_name" class="form-control" placeholder="أدخل اسم المعهد" value="{{ old('institute_name') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="example@email.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>رقم الهاتف <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" placeholder="أدخل رقم الهاتف" value="{{ old('phone') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>العنوان <span class="text-danger">*</span></label>
                        <input type="text" name="address" class="form-control" placeholder="أدخل عنوان المعهد" value="{{ old('address') }}" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>وصف المعهد</label>
                        <textarea name="institute_description" class="form-control" rows="3" placeholder="أدخل وصفًا للمعهد">{{ old('institute_description') }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>عن المعهد</label>
                        <textarea name="about_us" class="form-control" rows="3" placeholder="أدخل معلومات عن المعهد">{{ old('about_us') }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>صورة المعهد</label>
                        <input type="file" name="about_image" class="form-control" accept="image/*" onchange="previewImage(event)">
                        <img id="imagePreview" class="mt-3" src="#" style="max-width: 100px; display: none;" alt="معاينة الصورة">
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary px-5">حفظ</button>
                    <a href="{{ route('institute.index') }}" class="btn btn-danger px-5">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>


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
