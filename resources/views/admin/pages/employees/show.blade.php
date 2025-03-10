@extends('admin.layouts.app')

@section('title', 'تفاصيل الموظف')

@section('content')

<div class="page-wrapper"style="background-color: #F9F9FB;">
    <div class="content container-fluid">

        <!-- 📌 Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تفاصيل الموظف</h3>
                </div>
                <div class="col-auto text-end">
                    <a href="{{ route('employees.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> رجوع
                    </a>
                </div>
            </div>
        </div>

        <!-- 📌 Employee Profile Card -->
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <!-- Profile Image & Basic Info -->
                    <div class="col-md-4 text-center">
                        <div class="profile-image mb-3">
                            @if ($employee->image)
                                <img src="{{ asset('storage/' . $employee->image) }}" alt="Employee Image"
                                     class="img-fluid rounded-circle border shadow" width="120">
                            @else
                                <img src="{{ asset('images/default-avatar.png') }}" alt="No Image"
                                     class="img-fluid rounded-circle border shadow" width="120">
                            @endif
                        </div>
                        <h4 class="mb-1">{{ $employee->name_ar }}</h4>
                        <p class="text-muted">{{ $employee->emptype }}</p>
                        <span class="badge {{ $employee->state ? 'bg-success' : 'bg-danger' }}">
                            {{ $employee->state ? 'نشط' : 'غير نشط' }}
                        </span>
                    </div>

                    <!-- Employee Info Table -->
                    <div class="col-md-8">
                        <table class="table table-striped border">
                            <tbody>
                                <tr>
                                    <th> الاسم بالإنجليزية:</th>
                                    <td>{{ $employee->name_en }}</td>
                                </tr>
                                <tr>
                                    <th> رقم الهاتف:</th>
                                    <td>{{ $employee->phone }}</td>
                                </tr>
                                <tr>
                                    <th> العنوان:</th>
                                    <td>{{ $employee->address }}</td>
                                </tr>
                                <tr>
                                    <th> البريد الإلكتروني:</th>
                                    <td>{{ $employee->email ?? 'غير متوفر' }}</td>
                                </tr>
                                <tr>
                                    <th> الجنس:</th>
                                    <td>{{ $employee->gender == 'male' ? 'ذكر' : 'أنثى' }}</td>
                                </tr>
                                <tr>
                                    <th> تاريخ الميلاد:</th>
                                    <td>{{ $employee->birth_date }}</td>
                                </tr>
                                <tr>
                                    <th> مكان الميلاد:</th>
                                    <td>{{ $employee->birth_place }}</td>
                                </tr>
                                <tr>
                                    <th> نوع الوظيفة:</th>
                                    <td>{{ $employee->emptype }}</td>
                                </tr>
                                <tr>
                                    <th> الدور الوظيفي:</th>
                                    <td>{{ optional($employee->user->roles->first())->name ?? 'غير محدد' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <!-- 📌 Employee Qualifications -->
        <div class="card mt-3">
            <div class="card-body">
                <h4 class="mb-3">📜 المؤهلات العلمية</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>🏫 اسم المؤهل</th>
                            <th>🏢 الجهة المانحة</th>
                            <th>📅 تاريخ الحصول</th>
                            <th>📄 الشهادة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employee->qualifications as $qualification)
                            <tr>
                                <td>{{ $qualification->qualification_name }}</td>
                                <td>{{ $qualification->issuing_authority }}</td>
                                <td>{{ $qualification->obtained_date ?? 'غير متوفر' }}</td>
                                <td>
                                    @if ($qualification->certification)
                                        <a href="{{ asset('storage/' . $qualification->certification) }}" target="_blank">
                                            عرض الملف
                                        </a>
                                    @else
                                        لا يوجد شهادة
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">لا توجد مؤهلات مسجلة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body text-end">
                <!-- 🔹 Edit Button -->
                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> تعديل
                </a>
        
                <!-- 🔹 Activate/Deactivate Button -->
                <form action="{{ route('employees.toggleStatus', $employee->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $employee->state ? 'btn-success' : 'btn-danger' }}" 
                            style="border-radius: 50px; padding: 5px 15px;">
                        <i class="fas {{ $employee->state ? 'fa-check-circle' : 'fa-ban' }}"></i>
                        {{ $employee->state ? 'نشط' : 'غير نشط' }}
                    </button>
                </form>
            </div>
        </div>
        


    </div>
</div>

@endsection
