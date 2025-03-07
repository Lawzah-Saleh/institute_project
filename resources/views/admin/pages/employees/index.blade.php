@extends('admin.layouts.app')

@section('title', 'قائمة الموظفين')

@section('content')

<div class="page-wrapper">
<div class="content container-fluid">

    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">الموظفون</h3>
            </div>
        </div>
    </div>

    <!-- Search & Filter Form -->
    <div class="student-group-form">
        <form method="GET" action="{{ url('employees') }}">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <input type="text" name="search_id" class="form-control" placeholder="البحث بالرقم ..." value="{{ request('search_id') }}">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <input type="text" name="search_name" class="form-control" placeholder="البحث بالأسم ..." value="{{ request('search_name') }}">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label>عرض حسب نوع الوظيفة</label>

                    <div class="form-group">
                        <select name="emptype" class="form-control">
                            <option value="">-- عرض الجميع --</option>
                            <option value="teacher" {{ request('emptype') == 'teacher' ? 'selected' : '' }}>المعلمين</option>
                            <option value="employee" {{ request('emptype') == 'employee' ? 'selected' : '' }}>الموظفين العاديين</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="search-student-btn">
                        <button type="submit" class="btn btn-primary">بحث</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">

                    <!-- Table Header -->
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">قائمة الموظفين</h3>
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <a href="{{ url('employees/create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> إضافة موظف
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Table -->
                    <div class="table-responsive">
                        <table class="table border-0 table-hover table-center mb-0 datatable table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>الصورة</th>
                                    <th>الاسم بالإنجليزية</th>
                                    <th>الاسم بالعربية</th>
                                    <th>رقم الهاتف</th>
                                    <th>العنوان</th>
                                    <th>الجنس</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>نوع الوظيفة</th>
                                    <th>تاريخ الميلاد</th>
                                    <th>مكان الميلاد</th>
                                    <th class="text-end">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                <tr>
                                    <td>{{ $employee->id }}</td>
                                    <td>
                                        @if ($employee->image)
                                            <img src="{{ asset('storage/' . $employee->image) }}" alt="Employee Image" width="50">
                                        @else
                                            <span>لا توجد صورة</span>
                                        @endif
                                    </td>
                                    <td>{{ $employee->name_en }}</td>
                                    <td>{{ $employee->name_ar }}</td>
                                    <td>{{ $employee->phone }}</td>
                                    <td>{{ $employee->address }}</td>
                                    <td>{{ $employee->gender }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->emptype }}</td> <!-- Job Type -->
                                    <td>{{ $employee->Day_birth }}</td>
                                    <td>{{ $employee->place_birth }}</td>
                                    <td class="text-end">
                                        <div class="actions">
                                            <a href="{{ url('employees/'.$employee->id) }}" class="btn btn-sm bg-success-light me-2">
                                                <i class="feather-eye"></i>
                                            </a>
                                            <a href="{{ url('employees/'.$employee->id.'/edit') }}" class="btn btn-sm bg-danger-light">
                                                <i class="feather-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
</div>

@endsection
