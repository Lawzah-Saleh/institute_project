@extends('admin.layouts.app')

@section('title', ' الدورات المتاحة ')

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
                    <h3 class="page-title"> الدورات المتاحة</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto">
                    <a href="{{ route('course-sessions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> تهيئة دورة
                    </a>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>رقم الدورة المتاحة</th>
                                    <th>اسم الكورس</th>
                                    <th>المدرس</th>
                                    <th>تاريخ البداية</th>
                                    <th>تاريخ النهاية</th>
                                    <th>عدد الساعات يوميًا</th>
                                    <th>الوقت</th>
                                    <th>الحالة</th>
                                    <th class="text-end">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessions as $session)
                                <tr>
                                    <td>{{ $session->id }}</td>
                                    <td>{{ $session->course->course_name }}</td>
                                    <td>{{ $session->employee->name_ar ?? 'غير محدد' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($session->start_date)->format('d M Y') }}</td>
                                    <td>
                                        @if ($session->end_date)
                                            {{ \Carbon\Carbon::parse($session->end_date)->format('d M Y') }}
                                        @else
                                            <span class="text-danger">لم يتم حسابه</span>
                                        @endif
                                    </td>
                                    <td>{{ $session->daily_hours ?? 'غير محدد' }} ساعات</td>
                                    <td>{{ $session->start_time }} - {{ $session->end_time }}</td>
                                    <td>
                                        <form action="{{ route('course-sessions.toggle', $session->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $session->state ? 'btn-success' : 'btn-danger' }}">
                                                {{ $session->state ? 'نشطة' : 'غير نشطة' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('course-sessions.edit', $session->id) }}" class="btn btn-sm bg-success-light me-1">
                                                <i class="feather-edit"></i>
                                            </a>
                                            <a href="" class="btn btn-sm btn-primary me-2">
                                                <i class="feather-user-plus"></i> تسجيل طلاب
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">لا توجد دورات متاحة مضافة.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div> <!-- col-sm-12 -->
        </div> <!-- row -->

    </div> <!-- content -->
</div> <!-- page-wrapper -->

@endsection
