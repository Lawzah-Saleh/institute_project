

{{-- ظ/////////////////////////////////// --}}
@extends('dashboard-Student.layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                    <h3 class="page-title" style="color: #196098; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                        <i class="fas fa-user-circle"   style="margin-left: 15px; color: #196098; font-size: 1.2rem;"></i>
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
                        <img class="rounded-circle img-thumbnail" alt="User Image" src="{{asset('student/assets/img/profiles/avatar-01.jpg')}}">
                    </a>
                </div>
                <h4>Saeed Ahmed</h4>
                <p class="text-muted">Student   </p>
                <p><i class="fas fa-map-marker-alt"></i> Snaa'a Street, </p>
            </div>
            <div class="profile-menu">
                <ul class="nav nav-tabs nav-tabs-solid justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#personal_info"style="background-color: #196098">معلومات الشخصية</a>
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
                            <div class="row">
                                <p class="col-sm-4 text-muted">الاسم</p>
                                <p class="col-sm-8">Zacariah </p>
                            </div>
                            <div class="row">
                                <p class="col-sm-4 text-muted">تاريخ الميلاد</p>
                                <p class="col-sm-8">24 Jul 1983</p>
                            </div>
                            <div class="row">
                                <p class="col-sm-4 text-muted">الايميل</p>
                                <p class="col-sm-8">Zico7@example.com</p>
                            </div>
                            <div class="row">
                                <p class="col-sm-4 text-muted">رقم الموبايل</p>
                                <p class="col-sm-8">77-777-777-77</p>
                            </div>
                            <div class="row">
                                <p class="col-sm-4 text-muted">العنوان</p>
                                <p class="col-sm-8">صنعاء شارع الأربعين</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="password_tab">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">تغيير كلمة المرور</h5>
                            <form>
                                <div class="mb-3">
                                    <label for="old_password" class="form-label">كلمة المرور القديمة</label>
                                    <input type="password" id="old_password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                                    <input type="password" id="new_password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">تأكيد كلمة المرور</label>
                                    <input type="password" id="confirm_password" class="form-control">
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

<style>
    body {
        background: linear-gradient(135deg, #f3f4f6, #ffffff);
        font-family: 'Roboto', sans-serif;
    }

    .profile-header {
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .profile-image img {
        width: 150px;
        height: 150px;
    }

    .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>

@endsection





{{-- ///////////////////////// --}}
 