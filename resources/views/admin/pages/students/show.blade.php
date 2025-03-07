@extends('admin.layouts.app')

@section('title', 'Student Profile')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">ملف الطالب</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active">{{ $student->student_name_ar }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Student Profile -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Student Image -->
                    <div class="col-md-4 text-center">
                        <div class="profile-img">
                            <img src="{{ $student->image ? asset('storage/' . $student->image) : asset('images/default.png') }}"
                                 class="img-fluid rounded-circle shadow-lg"
                                 style="width: 200px; height: 200px; object-fit: cover;"
                                 alt="Student Image">
                        </div>
                        <h4 class="mt-3">{{ $student->student_name_ar }}</h4>
                    </div>

                    <!-- Student Details -->
                    <div class="col-md-8">
                        <h4>اسم الطالب: {{ $student->student_name_ar }}</h4>
                        <p><strong>القسم:</strong> {{ $student->section ? $student->section->name : 'غير متوفر' }}</p>
                        <p><strong>الدورة:</strong> {{ $student->course ? $student->course->course_name : 'غير متوفر' }}</p>
                        <p><strong>الجنس:</strong> {{ $student->gender }}</p>
                        <p><strong>البريد الإلكتروني:</strong> {{ $student->email }}</p>
                        <p><strong>رقم الهاتف:</strong> {{ $student->phone }}</p>
                        <p><strong>تاريخ الميلاد:</strong> {{ $student->Day_birth }}</p>
                        <p><strong>العنوان:</strong> {{ $student->address }}</p>
                        <p><strong>المؤهل:</strong> {{ $student->qulification }}</p>
                        <p><strong>الحالة:</strong> {{ $student->state }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
