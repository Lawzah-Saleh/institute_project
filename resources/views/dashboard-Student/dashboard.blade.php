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

    <!-- Attendance Percentage -->
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-header">
                <h4>Attendance</h4>
            </div>
            <div class="card-body">
                <p class="lead">{{ $attendancePercentage > 0 ? round($attendancePercentage, 2) . '%' : 'No attendance data available' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Enrolled Courses -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Your Enrolled Courses</h4>
            </div>
            <div class="card-body">
                @forelse($sessions as $session)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>{{ $session['course_name'] }}</h5>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $session['progress'] }}%" aria-valuenow="{{ $session['progress'] }}" aria-valuemin="0" aria-valuemax="100">{{ $session['progress'] }}%</div>
                        </div>
                    </div>
                </div>
                @empty
                <p>No enrolled courses found.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
