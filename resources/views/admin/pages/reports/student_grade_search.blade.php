@extends('admin.layouts.app')

@section('title', 'بحث عن درجات الطالب')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">بحث عن درجات الطالب</h3>
                </div>
            </div>
        </div>

        <!-- مربع البحث -->
        <form method="GET" action="{{ route('admin.reports.student_grade_search') }}" class="mb-4">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" name="search" id="search" class="form-control" placeholder="ابحث بالاسم" value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </div>
        </form>

        <!-- عرض النتائج -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">الطلاب</h5>
                <ul class="list-group">
                    @foreach($students as $student)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.reports.student_grade_details', $student->id) }}">
                                {{ $student->student_name_ar }} ({{ $student->student_name_en }})
                            </a>
                            <span class="badge badge-info">{{ $student->email }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
