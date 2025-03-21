@extends('dashboard-Student.layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                    <h3 class="page-title" style="color: #196098; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                        <i class="fas fa-user-circle" style="margin-left: 15px; color: #196098; font-size: 1.2rem;"></i>
                        تفاصيل الملف الشخصي   
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="profile-header text-center mb-4">
                <div class="profile-image mb-3">
                    <a href="#">
                        <img class="rounded-circle img-thumbnail" alt="User Image" 
                        src="{{ asset('storage/profile_images/' . ($student->image ?? 'default.jpg')) }}">
                                       </a>
                </div>
                <h4>{{ $student->student_name_en }}</h4>
                <p class="text-muted">طالب</p>
                <p><i class="fas fa-map-marker-alt"></i> {{ $student->address }}</p>
            </div>
            
            <!-- Personal Info Tab -->
            <div class="profile-menu">
                <ul class="nav nav-tabs nav-tabs-solid justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#personal_info" style="background-color: #196098">معلومات الشخصية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#password_tab">تغيير كلمة المرور</a>
                    </li>
                </ul>
            </div>
            
            <div class="tab-content">
                <div class="tab-pane fade show active" id="personal_info">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">التفاصيل الشخصية</h5>
                            <form action="{{ route('profile.student.update') }}" method="POST">
                                @csrf
                                <div class="row mb-3">
                                    <p class="col-sm-4 text-muted">الاسم (بالإنجليزية)</p>
                                    <input class="col-sm-8 form-control" type="text" name="student_name_en" value="{{ $student->student_name_en }}">
                                </div>
                                <div class="row mb-3">
                                    <p class="col-sm-4 text-muted">الاسم (بالعربية)</p>
                                    <input class="col-sm-8 form-control" type="text" name="student_name_ar" value="{{ $student->student_name_ar }}">
                                </div>
                                <div class="row mb-3">
                                    <p class="col-sm-4 text-muted">الايميل</p>
                                    <input class="col-sm-8 form-control" type="email" name="email" value="{{ $student->email }}">
                                </div>
                                <div class="row mb-3">
                                    <p class="col-sm-4 text-muted">رقم الموبايل</p>
                                    <input class="col-sm-8 form-control" type="text" name="phones" value="{{ $student->phones }}">
                                </div>
                                <div class="row mb-3">
                                    <p class="col-sm-4 text-muted">العنوان</p>
                                    <input class="col-sm-8 form-control" type="text" name="address" value="{{ $student->address }}">
                                </div>
                                <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Password Change Tab -->
                <div class="tab-pane fade" id="password_tab">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">تغيير كلمة المرور</h5>
                            <form action="{{ route('profile.student.updatePassword') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="old_password" class="form-label">كلمة المرور القديمة</label>
                                    <input type="password" name="old_password" id="old_password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary">حفظ</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
