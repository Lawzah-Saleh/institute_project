@extends('admin.layouts.app')

@section('title', 'تعديل الكورس')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- رسالة نجاح -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- عرض الأخطاء -->
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تعديل الدورة</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">الدورات</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('courses.update', $course->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>تفاصيل الدورات</span></h5>
                                </div>

                                <!-- رقم الكورس -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>رقم الدورة<span class="login-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $course->id }}" disabled>
                                    </div>
                                </div>

                                <!-- اسم الكورس -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>اسم الدورة <span class="login-danger">*</span></label>
                                        <input type="text" name="course_name" class="form-control" value="{{ $course->course_name }}" required>
                                    </div>
                                </div>

                                <!-- مدة الكورس (اختياري) -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>مدة الدورة (اختياري)</label>
                                        <input type="number" name="duration" class="form-control" value="{{ $course->duration }}">
                                    </div>
                                </div>

                                <!-- القسم -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>القسم <span class="login-danger">*</span></label>
                                        <select name="department_id" class="form-control" required>
                                            <option value="">اختر القسم</option>
                                            @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ $course->department_id == $department->id ? 'selected' : '' }}>
                                                {{ $department->department_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>وصف الدورة <span class="login-danger">*</span></label>
                                        <input type="text" name="description" class="form-control" value="{{ $course->description }}" required>
                                    </div>
                                </div>

                                <!-- الحالة -->
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>الحالة <span class="login-danger">*</span></label>
                                        <select name="state" class="form-control" required>
                                            <option value="1" {{ $course->state == 1 ? 'selected' : '' }}>نشطة</option>
                                            <option value="0" {{ $course->state == 0 ? 'selected' : '' }}>غير نشطة</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- زر التعديل -->
                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">تعديل الدورة</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
