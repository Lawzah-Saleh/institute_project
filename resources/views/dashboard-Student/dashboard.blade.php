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
<div class="row">
    <!-- Total Courses -->
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-header">
                <h4>Total Courses</h4>
            </div>
            <div class="card-body">
                <p class="lead">{{ $totalCourses > 0 ? $totalCourses : 'No courses enrolled' }}</p>
            </div>
        </div>
    </div>



<!-- Enrolled Courses -->
<!-- Dashboard Stats -->
<div class="row">
    <!-- Total Courses -->
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-header">
                <h4>إجمالي الدورات المسجلة</h4>
            </div>
            <div class="card-body">
                <p class="lead">
                    {{ $totalCourses > 0 ? $totalCourses : 'لم يتم التسجيل في أي دورة' }}
                </p>
            </div>
        </div>
    </div>
</div>


@endsection
