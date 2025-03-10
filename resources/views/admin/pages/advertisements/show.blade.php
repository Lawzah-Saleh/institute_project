@extends('admin.layouts.app')

@section('title', 'عرض الإعلان')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title">📢 تفاصيل الإعلان</h3>

        </div>

        <!-- 📋 جدول عرض تفاصيل الإعلان -->
        <div class="card shadow-sm">
            <div class="card-header text-white" style="background: rgba(25, 96, 152, 0.8);">
                <h4 class="mb-0"> {{ $advertisement->title }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th width="20%"> عنوان الإعلان:</th>
                            <td>{{ $advertisement->title }}</td>
                        </tr>
                        <tr>
                            <th> المحتوى:</th>
                            <td>{{ $advertisement->content }}</td>
                        </tr>
                        <tr>
                            <th> تاريخ النشر:</th>
                            <td>{{ \Carbon\Carbon::parse($advertisement->publish_date)->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th> تاريخ الانتهاء:</th>
                            <td>{{ \Carbon\Carbon::parse($advertisement->end_date)->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th> الحالة:</th>
                            <td>
                                @if ($advertisement->state)
                                    <span class="badge bg-success">نشط ✅</span>
                                @else
                                    <span class="badge bg-danger">منتهي ❌</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>🖼️ صورة الإعلان:</th>
                            <td>
                                <img src="{{ asset('storage/' . $advertisement->image) }}" class="img-fluid rounded shadow" style="max-width: 200px;" alt="صورة الإعلان">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('advertisements.edit', $advertisement->id) }}" class="btn btn-warning">
                تعديل
           </a>
            <a href="{{ route('advertisements.index') }}" class="btn btn-danger "> رجوع</a>
        </div>

    </div>
</div>

<style>
    .btn-warning {
        background-color: #e94c21;
        color: white;
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
