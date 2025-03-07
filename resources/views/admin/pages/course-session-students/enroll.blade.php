@extends('admin.layouts.app')

@section('title', 'تسجيل طلاب في الجلسة')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تسجيل طلاب في الجلسة</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('course-sessions.index') }}">جلسات الكورسات</a></li>
                        <li class="breadcrumb-item active">تسجيل طلاب</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5>الجلسة: {{ $session->course->course_name }} - المدرس: {{ $session->employee->name_ar }}</h5>
                        <form action="{{ route('course-sessions.store-enrollment', $session->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="students">اختر الطلاب:</label>
                                <select name="students[]" id="students" class="form-control" multiple>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ in_array($student->id, $enrolledStudents) ? 'selected' : '' }}>
                                            {{ $student->name_ar }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">تسجيل</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
