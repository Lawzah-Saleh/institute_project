@extends('admin.layouts.app')

@section('title', 'تسجيل الطلاب في الجلسة')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تسجيل الطلاب في الجلسة</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('course-sessions.index') }}">جلسات الكورسات</a></li>
                        <li class="breadcrumb-item active">تسجيل الطلاب</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('course-sessions.enroll', $session->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12">
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
                                </div>
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary">تسجيل الطلاب</button>
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
