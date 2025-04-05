@extends('admin.layouts.app')

@section('title', 'إدارة الجهات السداد')

@section('content')
<div class="page-wrapper" style="margin-right: 100px;padding:50px 20px;width: calc(105% - 150px);background:#f7f7fa;">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">إدارة الجهات السداد</h3>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col ">
                        <a href="{{ route('payment_sources.create') }}" class="btn btn-primary">إضافة جهة سداد جديدة</a>
                    </div>
                </div>

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>اسم الجهة</th>
                            <th>الحالة</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paymentSources as $paymentSource)
                        <tr>
                            <td>{{ $paymentSource->name }}</td>
                            <td>
                                <span class="badge {{ $paymentSource->status == 'active' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $paymentSource->status == 'active' ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('payment_sources.edit', $paymentSource->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                                <form action="{{ route('payment_sources.destroy', $paymentSource->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
