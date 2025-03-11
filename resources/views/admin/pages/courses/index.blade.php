@extends('admin.layouts.app')

@section('title', 'الدورات')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid" style="background-color: #F9F9FB;">

        <!-- رسالة نجاح -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">الدورات</h3>
                </div>
            </div>
        </div>

        <div class="student-group-form">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <form method="GET" action="{{ route('courses.index') }}">
                            <label for="department_id">اختر قسم:</label>
                            <select name="department_id" id="department_id" class="form-control">
                                <option value="">كل الأقسام</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary mt-2">عرض</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">قائمة الدورات</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ route('courses.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> إضافة دورة
                                    </a>
                                </div>
                            </div>
                        </div>

                        <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                            <thead class="student-thread">
                                <tr>
                                    <th>رقم الدورة</th>
                                    <th>اسم الدورة</th>
                                    <th>السعر (نشط)</th>
                                    <th>عدد ساعات الدراسة</th>
                                    <th>القسم</th>
                                    <th>الوصف</th>
                                    <th class="text-end">الإجراءات</th>
                                    <th>الحالة</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courses as $course)
                                <tr>
                                    <td>{{ $course->id }}</td>
                                    <td>{{ $course->course_name }}</td>
                                    <td>{{ $course->latestActivePrice->price ?? 'لا يوجد سعر نشط' }}</td>
                                    <td>{{ $course->duration }}</td>
                                    <td>{{ $course->department->department_name }}</td>
                                    <td>{{ $course->description }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm bg-success-light me-1">
                                                <i class="feather-edit"></i>
                                            </a>

                                        </div>
                                    </td>
                                    <td>
                                        <form action="{{ route('courses.toggle', $course->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                   -         @if ($course->state === 1)
                                                <button type="submit" class="btn btn-sm btn-success">نشطة</button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-danger">غير نشطة</button>
                                            @endif
                                        </form>
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">لا توجد دورات للقسم المحدد.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
