@extends('admin.layouts.app')

@section('title', 'تعديل بيانات المعهد')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="card shadow-sm p-4">
            <div class="card-header text-white text-center"style="background: rgba(25, 96, 152, 0.8);">
                <h4> تعديل بيانات المعهد</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('institute.update', $institute->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- اسم المعهد -->
                        <div class="col-md-6 mb-3">
                            <label>  اسم المعهد <span class="text-danger">*</span></label>
                            <input type="text" name="institute_name" class="form-control" value="{{ old('institute_name', $institute->institute_name) }}" required>
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div class="col-md-6 mb-3">
                            <label>  البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $institute->email) }}" required>
                        </div>

                        <!-- رقم الهاتف -->
                        <div class="col-md-6 mb-3">
                            <label>  رقم الهاتف <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $institute->phone) }}" required>
                        </div>

                        <!-- العنوان -->
                        <div class="col-md-6 mb-3">
                            <label>  العنوان <span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $institute->address) }}" required>
                        </div>

                        <!-- وصف المعهد -->
                        <div class="col-md-12 mb-3">
                            <label>  وصف المعهد</label>
                            <textarea name="institute_description" class="form-control" rows="3">{{ old('institute_description', $institute->institute_description) }}</textarea>
                        </div>

                        <!-- عن المعهد -->
                        <div class="col-md-8 mb-3">
                            <label>  عن المعهد</label>
                            <textarea name="about_us" class="form-control" rows="3">{{ old('about_us', $institute->about_us) }}</textarea>
                        </div>

                        <!-- صورة المعهد -->
                        <div class="col-md-4 text-center">
                            <label>  صورة عن المعهد</label>
                            <input type="file" name="about_image" class="form-control" accept="image/*" onchange="previewImage(event)">
                            <img id="imagePreview" class="mt-3 img-fluid rounded shadow" src="{{ asset($institute->about_image) }}" style="max-width: 100px;" alt="صورة المعهد">
                        </div>
                    </div>

                    <!-- أزرار الحفظ والإلغاء -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary px-5"> حفظ التعديلات</button>
                        <a href="{{ route('institute.index') }}" class="btn btn-danger px-5"> إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        background-color: #f8f9fa;
        border-radius: 10px;
    }
    .card-header {
        font-size: 18px;
        font-weight: bold;
        padding: 15px;
        border-radius: 10px 10px 0 0;
    }
    .btn-primary {
        background-color: #196098;
        border-color: #196098;
        color: white;
        font-size: 16px;
        padding: 10px 20px;
        border-radius: 5px;
    }
    .btn-primary:hover {
        background-color: #154a7a;
    }
    .btn-danger {
        background-color: #e94c21;
        border-color: #e94c21;
        color: white;
        font-size: 16px;
        padding: 10px 20px;
        border-radius: 5px;
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
