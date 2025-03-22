@extends('dashboard-Student.layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                    <h3 class="page-title" style="color: #196098; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                        <i class="fas fa-user-circle" style="margin-left: 15px; color: #196098; font-size: 1.2rem;"></i>
                        تفاصيل الملف الشخصي
                    </h3>
                </div>
            </div>
        </div>
    </div>
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-warning">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


    <div class="row">
        <div class="col-md-12">
            <div class="profile-header text-center mb-4">
                <div class="profile-image mb-3">
                    <img src="{{ asset('storage/' . $student->image) }}" alt="User Image" class="rounded-circle img-thumbnail" width="150">

                </div>
                <h4>{{ $student->student_name_en }}</h4>
                <p class="text-muted">طالب</p>
                <p><i class="fas fa-map-marker-alt"></i> {{ $student->address }}</p>
            </div>

            <!-- Personal Info Tab -->
            <div class="profile-menu">
                <ul class="nav nav-tabs nav-tabs-solid justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#personal_info" style="background-color: #196098">معلومات الشخصية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#password_tab">تغيير كلمة المرور</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="personal_info">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">التفاصيل الشخصية</h5>
                            <form action="{{ route('profile.student.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">اسم الطالب (بالإنجليزية)</label>
                                    <input type="text" class="form-control" name="student_name_en" value="{{ $student->student_name_en }}" required>
                                </div>

                                <!-- Arabic Name (Displayed but Not Editable) -->
                                <div class="mb-3">
                                    <label class="form-label">اسم الطالب (بالعربية)</label>
                                    <input type="text" class="form-control" value="{{ $student->student_name_ar }}" disabled>
                                </div>

                                <!-- Gender (Displayed but Not Editable) -->
                                <div class="mb-3">
                                    <label class="form-label">الجنس</label>
                                    <input type="text" class="form-control" value="{{ $student->gender }}" disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <input type="email" class="form-control" name="email" value="{{ $student->email }}">
                                </div>

                                <div id="phone-fields">
                                    @php
                                    $phones = json_decode($student->phones, true) ?? [''];
                                @endphp

                                @foreach ($phones as $phone)
                                <input type="text" name="phones[]" value="{{ old('phones.0', $phones[0] ?? '') }}" class="form-control">
                                @endforeach

                                <!-- For adding a new number -->
                                <input type="text" name="phones[]" class="form-control mb-2" placeholder="رقم هاتف جديد">


                                </div>


                                <div class="mb-3">
                                    <label class="form-label">المؤهل</label>
                                    <input type="text" class="form-control" name="qualification" value="{{ $student->qualification }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">تاريخ الميلاد</label>
                                    <input type="date" class="form-control" name="birth_date" value="{{ $student->birth_date }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">مكان الميلاد</label>
                                    <input type="text" class="form-control" name="birth_place" value="{{ $student->birth_place }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">العنوان</label>
                                    <input type="text" class="form-control" name="address" value="{{ $student->address }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">الصورة الشخصية</label>
                                    <input type="file" name="image" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                            </form>


                        </div>
                    </div>
                </div>

                <!-- Password Change Tab -->
                <div class="tab-pane fade" id="password_tab">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">تغيير كلمة المرور</h5>
                            <form method="POST" action="{{ route('profile.student.password.update') }}">
                                @csrf
                            
                                <div class="mb-3">
                                    <label class="form-label">كلمة المرور الحالية</label>
                                    <input type="password" name="old_password" class="form-control" required>
                                </div>
                            
                                <div class="mb-3">
                                    <label class="form-label">كلمة المرور الجديدة</label>
                                    <input type="password" name="new_password" class="form-control" required>
                                </div>
                            
                                <div class="mb-3">
                                    <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" required>
                                </div>
                            
                                <button type="submit" class="btn btn-primary">حفظ</button>
                            </form>
                            
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
