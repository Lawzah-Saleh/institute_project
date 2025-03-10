@extends('admin.layouts.app')

@section('title', 'قائمة الموظفين')

@section('content')

<div class="page-wrapper" style="background-color: #F9F9FB;">
    <div class="content container-fluid">



        <!-- Search & Filter Form -->
        <div class="card filter-card">
            <div class="card-body">
                <form method="GET" action="{{ url('employees') }}">
                    <div class="row">
                        <!-- Search by ID -->
                        <div class="col-md-4">
                            <label>البحث بالرقم</label>
                            <input type="text" name="search_id" class="form-control" placeholder="رقم الموظف ..." value="{{ request('search_id') }}">
                        </div>

                        <!-- Search by Name -->
                        <div class="col-md-4">
                            <label>البحث بالاسم</label>
                            <input type="text" name="search_name" class="form-control" placeholder="اسم الموظف ..." value="{{ request('search_name') }}">
                        </div>

                        <!-- Filter by Job Type -->
                        <div class="col-md-4">
                            <label>نوع الوظيفة</label>
                            <select name="emptype" class="form-control">
                                <option value="">-- عرض الجميع --</option>
                                <option value="teacher" {{ request('emptype') == 'teacher' ? 'selected' : '' }}>المعلمين</option>
                                <option value="employee" {{ request('emptype') == 'employee' ? 'selected' : '' }}>الموظفين الإداريين</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-12 text-end mt-3">
                            <button type="submit" class="btn btn-primary">بحث</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Employee Table -->
        <div class="card">
            <div class="card-body ">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">قائمة الموظفين</h3>
                        </div>
                        <div class="col-auto text-start"> <!-- Changed from text-end to text-start -->
                            <a href="{{ url('employees/create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة موظف
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped datatable">
                        <thead>
                            <tr>
                                <th>#</th>
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
                                    <td>{{ $loop->iteration }}</td> <!-- Auto-incrementing ID -->
                                    <td>
                                        @if ($employee->image)
                                            <img src="{{ asset('storage/' . $employee->image) }}" alt="صورة الموظف" width="50" class="rounded-circle">
                                        @else
                                            <span class="text-muted">لا توجد صورة</span>
                                        @endif
                                    </td>
                                    <td>{{ $employee->name_en }}</td>
                                    <td>{{ $employee->name_ar }}</td>
                                    <td>{{ $employee->phone }}</td>
                                    <td>{{ $employee->address }}</td>
                                    <td>{{ $employee->gender == 'male' ? 'ذكر' : 'أنثى' }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>
                                        <span class="badge bg-info text-white">
                                            {{ $employee->emptype == 'teacher' ? 'معلم' : 'موظف إداري' }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($employee->birth_date)->format('Y-m-d') }}</td>
                                    <td>{{ $employee->birth_place }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('employees.toggleStatus', $employee->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $employee->state ? 'btn-success' : 'btn-danger' }}" 
                                                    style="border-radius: 50px; padding: 5px 15px;">
                                                <i class="fas {{ $employee->state ? 'fa-check-circle' : 'fa-ban' }}"></i>
                                                {{ $employee->state ? 'نشط' : 'غير نشط' }}
                                            </button>
                                        </form>
                                    </td>
                                    
                                    
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="{{ url('employees/'.$employee->id) }}" class="btn btn-sm">
                                                <i class="feather-eye"></i>
                                            </a>
                                            <a href="{{ url('employees/'.$employee->id.'/edit') }}" class="btn btn-sm">
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

@endsection
