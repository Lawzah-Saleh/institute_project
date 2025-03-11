@extends('admin.layouts.app')

@section('title', 'الأقسام')

@section('content')



<div class="page-wrapper">

    <div class="content container-fluid" style="background-color: #F9F9FB;">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">الأقسام</h3>
                </div>
            </div>
        </div>

        <div class="student-group-form">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <form method="GET" action="{{ route('departments.index') }}">
                            <input type="text" name="search" class="form-control" placeholder="بحث بواسطة اسم القسم..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary mt-2">بحث</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>




        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">الأقسام</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ route('departments.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                            <thead class="student-thread">
                                <tr>
                                    <th>رقم القسم</th>
                                    <th>أسم القسم</th>
                                    <th>معلومات القسم</th>
                                    <th class="text-end">الاجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departments as $department)
                                <tr>
                                    <td>{{ $department->id }}</td>
                                    <td>{{ $department->department_name }}</td>
                                    <td>{{ $department->department_info }}</td>
                                    <td>
                                        <div class="d-flex justify-content-right">
                                            <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-sm bg-success-light me-1">
                                                <i class="feather-edit"></i>
                                            </a>

                                        </div>
                                        <td>
                                            <div class="d-flex justify-content-right">
                                                <form action="{{ route('departments.toggle', $department->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if ($department->state)
                                                        <button type="submit" class="btn btn-sm btn-success">نشط</button>
                                                    @else
                                                        <button type="submit" class="btn btn-sm btn-danger">غير نشط</button>
                                                    @endif
                                                </form>
                                            </div>
                                        </td>

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">لا توجد أقسام متوفرة.</td>
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
