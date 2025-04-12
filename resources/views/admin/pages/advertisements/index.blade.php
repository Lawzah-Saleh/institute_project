@extends('admin.layouts.app')

@section('title', 'إدارة الإعلانات')

@section('content')

<div class="page-wrapper" style="background-color: #f7f7fa">
    <div class="content container-fluid">
        
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">إدارة الإعلانات</h3>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('advertisements.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="🔍  بحث عن إعلان...  بواسطة عنوان الأعلان" value="">
                </div>
                <div class="col-md-4 text-end">
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </div>
        </form>

        <!-- 📋 Advertisements List -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">قائمة الإعلانات</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto">
                                    <a href="{{ route('advertisements.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> إضافة إعلان جديد
                                    </a>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>رقم الإعلان</th>
                                    <th>عنوان الأعلان</th>
                                    <th>محتوى الأعلان</th>
                                    <th>تاريخ النشر</th>
                                    <th>تاريخ الانتهاء</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($advertisements as $advertisement)
                                <tr>
                                    <td>{{ $advertisement->id }}</td>
                                    <td>{{ $advertisement->title }}</td>
                                    <td>{{ \Str::limit($advertisement->content, 100, '...') }}</td>
                                    <td>{{ $advertisement->publish_date }}</td>
                                    <td>{{ $advertisement->end_date }}</td>
                                    <td>
                                        @if ($advertisement->state)
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-danger">منتهي</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('advertisements.show', $advertisement->id) }}" class="btn btn-primary">
                                            <i class="feather-eye"></i> عرض
                                        </a>

                                        <a href="{{ route('advertisements.edit', $advertisement->id) }}" class="btn  btn-primary">
                                            <i class="feather-edit"></i> تعديل
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">لا يوجد إعلانات مضافة بعد.</td>
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
<style>
    .btn-primary {
        background-color:rgba(25, 96, 152, 0.8);;
        color: white;
        border-color:rgba(25, 96, 152, 0.4);;
    }
    .btn-primary:hover {
        background-color: #196098;
    }

</style>
@endsection
