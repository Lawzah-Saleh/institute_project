@extends('dashboard-Student.layouts.app')

@section('title', 'Student Dashboard')

@section('content')

<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                <h3 class="page-title" style="color: #ff9800; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                    <i class="fas fa-user-graduate" style="margin-right: 15px; color: #ff9800; font-size: 1.2rem;"></i>
                    مرحبًا بك، {{ Auth::user()->name }} !
                </h3>
                <ul class="breadcrumb" style="margin-top: 10px;">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #ff9800; font-size: 1rem;">الصفحة الرئيسية</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Stats -->
<div class="row" style="display: flex;">
    <!-- Total Courses -->
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="mycard">
            <div class="card-body">
                <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                        <h6> Total Courses</h6>

                        <h3>{{ $totalCourses > 0 ? $totalCourses : 'No courses enrolled' }}</h3>
                    </div>
                    <div class="icon_box">
                        <img src="{{ asset('admin/assets/img/icons/dash-icon-01.png') }}" alt="Dashboard Icon" style="height: 70px;width:70px">
                    </div>
                </div>
            </div>
        </div>
    </div>




<!-- Enrolled Courses -->
<!-- Dashboard Stats -->
    <!-- Total Courses -->
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="mycard">
            <div class="card-body">
                <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                        <h6>إجمالي الدورات المسجلة</h6>
                        <h3>
                            {{ $totalCourses > 0 ? $totalCourses : 'لم يتم التسجيل في أي دورة' }}
                        </h3>
                    </div>
                    <div class="icon_box">
                        <img src="{{asset('admin/assets/img/icons/dash-icon-04.svg')}}" alt="Dashboard Icon" style="height: 50px;width:70px">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection
<style>
    .mycard {
  background-color: #ffffff;
  border-radius: 40px; /* لتنعيم الزوايا */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  width: 370px; /* عرض البطاقة */
  height: 150px; /* ارتفاع البطاقة */
  text-align: left;
  padding: 20px;
  transition: transform 0.3s ease, box-shadow 0.3s ease; /* تأثير الحركة */
}

.icon_box img {
  width: 50px;
  height: 50px;
  margin-bottom: 10px;
}

.mycard p {
  font-size: 18px;
  font-weight: bold;
}

.mycard span {
  font-size: 24px;
  color: #007bff;
}
.mycard:hover {
  transform: scale(1.1); /* تكبير البطاقة بنسبة 10% */
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* ظل أقوى */
}
.icon_box{
  background-color: #faece8;
  width: 80px; /* عرض الحاوية */
  height: 80px; /* ارتفاع الحاوية */
  margin: 0 auto; /* توسيط الصورة داخل البطاقة */
  overflow: hidden; /* إخفاء أي محتوى زائد عن الحاوية */
  border-radius: 30%; /* اختياريا: لجعل الحاوية دائرية */
  margin-left: 10px;
}
</style>
