@extends('Teacher-dashboard.layouts.app')

@section('title', ' profile')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">الملف الشخصي</h3>
                    {{-- <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ul> --}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="profile-header">
                    <div class="row ">
                        <div class="col-auto profile-image">
                            <a href="#">
                                <img class="rounded-circle" alt="User Image"
                                    src="{{asset('Teacher/assets/img/profiles/profile-t.png')}}">
                            </a>
                        </div>

                        <div class="col  ">
                            <h4 class="">John Doe</h4>
                            <h6 class="">UI/UX Design Team</h6>
                            <div class=""><i class="fas fa-map-marker-alt"></i> Florida, United
                                States</div>
                            <div class="">Lorem ipsum dolor sit amet.</div>
                        </div>

                    </div>
                </div>




                <div class="profile-menu">
                    <ul class="nav nav-tabs nav-tabs-solid " >
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#per_details_tab" >معلومات</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#password_tab">كلمة المرور</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content profile-tab-cont">

                    <div class="tab-pane fade show active" id="per_details_tab">

                        <div class="row">
                            <div class="col-lg-9">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title d-flex justify-content-between">
                                            <span>Personal Details</span>

                                        </h5>
                                        <div class="row">
                                            <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">الاسم</p>
                                            <p class="col-sm-9">John Doe</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">تاريخ الميلاد</p>
                                            <p class="col-sm-9">24 Jul 1983</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">الايميل</p>
                                            <p class="col-sm-9"><a href="/cdn-cgi/l/email-protection"
                                                    class="__cf_email__"
                                                    data-cfemail="a1cbcec9cfc5cec4e1c4d9c0ccd1cdc48fc2cecc">[email&#160;protected]</a>
                                            </p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">رقم الموبايل</p>
                                            <p class="col-sm-9">305-310-5857</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-3 text-muted text-sm-end mb-0">العنوان</p>
                                            <p class="col-sm-9 mb-0">4663 Agriculture Lane,<br>
                                                Miami,<br>
                                                Florida - 33165,<br>
                                                United States.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">







                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title d-flex justify-content-between">
                                            <span>المؤهلات</span>

                                        </h5>
                                        <div class="skill-tags">
                                            <span>Html5</span>
                                            <span>CSS3</span>
                                            <span>WordPress</span>
                                            <span>Javascript</span>
                                            <span>Android</span>
                                            <span>iOS</span>
                                            <span>Angular</span>
                                            <span>PHP</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>


                    <div id="password_tab" class="tab-pane fade">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">تغيير كلمة المرور</h5>
                                <div class="row">
                                    <div class="col-md-10 col-lg-6">
                                        <form>
                                            <div class="form-group">
                                                <label>كلمة المرور القديمة</label>
                                                <input type="password" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>كلمة المرور الجديدة</label>
                                                <input type="password" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>تأكيد كلمة المرور</label>
                                                <input type="password" class="form-control">
                                            </div>
                                            <button class="btn btn-primary"  style=" background: #e94c21" type="submit">حفظ</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</div>

@endsection
