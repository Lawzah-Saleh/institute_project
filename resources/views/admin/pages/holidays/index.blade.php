@extends('admin.layouts.app')

@section('title', 'أيام الإجازة')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">الإجازات</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active">الإجازات</li>
                    </ul>
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
                                    <h3 class="page-title">قائمة الإجازات</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto">
                                    <a href="{{ route('holidays.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة إجازة</a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 table-hover table-center mb-0 datatable table-striped">
                                <thead>
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>اسم الإجازة</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($holidays as $holiday)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($holiday->date)->format('d M Y') }}</td>
                                            <td><h2>{{ $holiday->name }}</h2></td>
                                            <td>
                                                @if ($holiday->state)
                                                    <span class="badge bg-success">مفعلة</span>
                                                @else
                                                    <span class="badge bg-danger">غير مفعلة</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('holidays.edit', $holiday->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                                                <form action="{{ route('holidays.toggle', $holiday->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $holiday->state ? 'btn-danger' : 'btn-success' }}">
                                                        {{ $holiday->state ? 'إلغاء التفعيل' : 'تفعيل' }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- table-responsive -->

                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div> <!-- col-sm-12 -->
        </div> <!-- row -->

    </div> <!-- content -->
</div> <!-- page-wrapper -->

@endsection
