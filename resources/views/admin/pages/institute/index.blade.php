@extends('admin.layouts.app')

@section('title', 'عرض معلومات المعهد')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title"> تفاصيل المعهد</h3>

        </div>

        <div class="card shadow-sm">
            <div class="card-header text-white" style="background: rgba(25, 96, 152, 0.8);">
                <h4 class="mb-0"> {{ $institute->institute_name }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th width="20%"> اسم المعهد:</th>
                            <td>{{ $institute->institute_name }}</td>
                        </tr>
                        <tr>
                            <th> البريد الإلكتروني:</th>
                            <td>
                                <a href="mailto:{{ $institute->email }}">{{ $institute->email }}</a>
                            </td>
                        </tr>
                        <tr>
                            <th> رقم الهاتف:</th>
                            <td>{{ $institute->phone }}</td>
                        </tr>
                        <tr>
                            <th> العنوان:</th>
                            <td>{{ $institute->address }}</td>
                        </tr>
                        <tr>
                            <th> الوصف:</th>
                            <td>{{ $institute->institute_description }}</td>
                        </tr>
                        <tr>
                            <th> عن المعهد:</th>
                            <td class="d-flex justify-content-between">
                                <span>{{ $institute->about_us }}</span>
                                @if ($institute->about_image)
                                    <img src="{{ asset('storage/' . $institute->about_image) }}"
                                         class="img-fluid rounded shadow"
                                         style="max-width: 200px; height: auto;"
                                         alt="صورة المعهد">
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 🔙 زر العودة -->
        <div class="mt-4 text-center">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-danger px-5"> رجوع</a>
            <a href="{{ route('institute.edit', $institute->id) }}" class="btn btn-warning">
                 تعديل
            </a>
        </div>

    </div>
</div>

<style>

    .btn-warning {
        background-color: #e94c21;
        color: #fff;
        border-color: #e94c21;
    }
    .btn-warning:hover {
        background-color: #d1401a;
    }
    .btn-danger {
        background-color: #e94c21;
        border-color: #e94c21;
    }
    .btn-danger:hover {
        background-color: #d1401a;
    }
</style>

@endsection
