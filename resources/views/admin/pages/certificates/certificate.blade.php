@extends('admin.layouts.app')

@section('title', 'إصدار الشهادة')

@section('content')
    <div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">إصدار الشهادة</h3>
                    </div>
                </div>
            </div>

            <div>
                <div class="card-body" style="background: url('/assets/img/certificate.png') no-repeat center center;  width: 100%; height: 100vh;">
                    <!-- هنا يمكنك إضافة المحتوى الذي تريد ظهوره فوق الصورة -->
                    <div class="certificate-content">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
.card-body {
    background: url('/assets/img/certificate.png') no-repeat center center;
    background-size: cover;
    width: 100%;
    height: 100vh;
    border: 8px solid #196098; /* تغيير اللون إلى اللون الأزرق للمشروع */
    border-radius: 15px; /* جعل الحواف دائرية */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* إضافة ظل للعنصر */
    padding: 20px; /* إضافة بعض المسافة حول المحتوى داخل العنصر */
}
</style>