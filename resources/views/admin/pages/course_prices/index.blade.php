@extends('admin.layouts.app')

@section('title', 'أسعار الدورات')

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
                    <h3 class="page-title">أسعار الدورات</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                    <a href="{{ route('course-prices.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة سعر
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
                                    <th>رقم السعر</th>
                                    <th>اسم الدورة</th>
                                    <th>السعر</th>
                                    <th>تاريخ السعر</th>
                                    <th>تاريخ الموافقة</th>
                                    <th>الحالة</th>
                                    <th class="text-end">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coursePrices as $price)
                                <tr>
                                    <td>{{ $price->id }}</td>
                                    <td>{{ $price->course->course_name }}</td>
                                    <td>{{ $price->price }}</td>
                                    <td>{{ $price->date }}</td>
                                    <td>{{ $price->price_approval }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('course-prices.edit', $price->id) }}" class="btn btn-sm bg-success-light me-1">
                                                <i class="feather-edit"></i>
                                            </a>
                                            {{-- <form action="{{ route('course-prices.destroy', $price->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm bg-danger-light" onclick="return confirm('هل أنت متأكد من حذف هذا السعر؟');">
                                                    <i class="feather-trash"></i>
                                                </button>
                                            </form> --}}
                                        </div>
                                    </td>
                                    <td>
                                        <form action="{{ route('course-prices.toggle', $price->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            @if ($price->state)
                                                <button type="submit" class="btn btn-sm btn-success">نشط</button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-danger">غير نشط</button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @if($coursePrices->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center">لا توجد أسعار مضافة.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
