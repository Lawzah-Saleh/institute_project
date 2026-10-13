@extends('Teacher-dashboard.layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid" style="background-color: #f9f9fb">

      

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
            </div>
        @endif

      
        <div class="flex justify-center mb-4">
            <div class="bg-white shadow-md rounded-lg p-4 text-center w-full md:w-2/3">
                <h3 class="text-2xl font-bold text-gray-700">الملف الشخصي</h3>
            </div>
        </div>


        <div class="bg-white shadow-md rounded-lg p-4 mb-7 d-flex align-items-center">
            <img src="{{ $employee->image && file_exists(public_path('storage/' . $employee->image))
                ? asset('storage/' . $employee->image)
                : asset('Teacher/assets/img/profiles/profile-t.png') }}"
                class="rounded-circle me-4" width="180" height="180" alt="User Image"
                style="width: 160px; height: 160px; object-fit: cover; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.2); margin-left: 20px;">

            <div>
                <h4 class="mb-0">{{ $employee->name_ar ?? 'الاسم غير متوفر' }}</h4>
                <small class="text-muted">{{ $employee->emptype ?? '' }}</small><br>
                <span class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $employee->address ?? '' }}</span>
            </div>
        </div>

        <ul class="nav nav-tabs nav-tabs-solid mb-3">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#per_details_tab">معلومات</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#qualifications_tab">المؤهلات العلمية</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#password_tab">كلمة المرور</a>
            </li>
        </ul>


        <div class="tab-content profile-tab-cont">
            {{-- تبويب المعلومات --}}
            <div class="tab-pane fade show active" id="per_details_tab">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title">المعلومات الشخصية</h5>
                            <a href="{{ url('edit-profile-T') }}" class="btn btn-link">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>

                        <div class="col-md-10">
                            <table class="table table-striped border">
                                <tbody>
                                    <tr><th>الاسم بالعربي:</th><td>{{ $employee->name_ar }}</td></tr>
                                    <tr><th>رقم الهاتف:</th>
                                        <td>{{ $employee->phones ?? '-' }}</td>

                                    </tr>
                                    <tr><th>العنوان:</th><td>{{ $employee->address }}</td></tr>
                                    <tr><th>البريد الإلكتروني:</th><td>{{ $employee->email ?? 'غير متوفر' }}</td></tr>
                                    <tr><th>الجنس:</th><td>{{ $employee->gender == 'male' ? 'ذكر' : 'أنثى' }}</td></tr>
                                    <tr><th>تاريخ الميلاد:</th><td>{{ $employee->birth_date }}</td></tr>
                                    <tr><th>مكان الميلاد:</th><td>{{ $employee->birth_place }}</td></tr>
                                    <tr><th>نوع الوظيفة:</th><td>{{ $employee->emptype }}</td></tr>
                                    <tr><th>الدور الوظيفي:</th>
                                        <td>{{ optional($employee->user->roles->first())->name ?? 'غير محدد' }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


              <!-- 📌 Employee Qualifications -->
              <div class="tab-pane fade" id="qualifications_tab">
                <div class="card mt-3">
                    <div class="card-body">

                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title">📜 المؤهلات العلمية</h5>
                        </div>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>🏫 اسم المؤهل</th>
                                    <th>🏢 الجهة المانحة</th>
                                    <th>📅 تاريخ الحصول</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employee->qualifications as $qualification)
                                    <tr>
                                        <td>{{ $qualification->qualification_name }}</td>
                                        <td>{{ $qualification->issuing_authority }}</td>
                                        <td>{{ $qualification->obtained_date ?? 'غير متوفر' }}</td>

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
            </div>









            {{-- تبويب كلمة المرور --}}
            <div id="password_tab" class="tab-pane fade">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title">تغيير كلمة المرور</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-lg-6">
                                <form method="POST" action="{{ route('teacher.change-password') }}">
                                    @csrf

                                  <!-- كلمة المرور (داخل تبويب تغيير كلمة المرور) -->
<div class="form-group mb-3">
    <label>كلمة المرور القديمة</label>
    <div class="position-relative">
        <input type="password" id="old_password" name="old_password" class="form-control ps-5">
        <span class="position-absolute top-50 start-0 translate-middle-y ms-3" onclick="togglePassword(this, 'old_password')" style="cursor: pointer;">
            <i class="fas fa-eye"></i>
        </span>
    </div>
</div>

<div class="form-group mb-3">
    <label>كلمة المرور الجديدة</label>
    <div class="position-relative">
        <input type="password" id="new_password" name="new_password" class="form-control ps-5">
        <span class="position-absolute top-50 start-0 translate-middle-y ms-3" onclick="togglePassword(this, 'new_password')" style="cursor: pointer;">
            <i class="fas fa-eye"></i>
        </span>
    </div>
</div>

<div class="form-group mb-3">
    <label>تأكيد كلمة المرور</label>
    <div class="position-relative">
        <input type="password" id="confirm_password" name="confirm_password" class="form-control ps-5">
        <span class="position-absolute top-50 start-0 translate-middle-y ms-3" onclick="togglePassword(this, 'confirm_password')" style="cursor: pointer;">
            <i class="fas fa-eye"></i>
        </span>
    </div>
</div>


                                    <button type="submit" class="btn" style="background-color: #196098; color: white; width: 100px;">حفظ</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- تصميم الحقل والأيقونة --}}
<style>
    .form-control.ps-5 {
        padding-left: 2.5rem !important;
    }
</style>


{{-- جافاسكربت تبديل الرؤية --}}



<script>
    function togglePassword(iconWrapper, inputId) {
        const input = document.getElementById(inputId);
        const icon = iconWrapper.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }
</script>


@endsection
