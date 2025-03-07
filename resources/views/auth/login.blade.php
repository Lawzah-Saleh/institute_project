@extends('layouts.app') {{-- استخدم القالب الأساسي لمشروعك --}}

@section('title', 'تسجيل الدخول') {{-- تعيين عنوان الصفحة --}}

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh; direction: rtl; background: #f4f4f4;">
    <div class="row w-100">
        <div class="col-lg-5 mx-auto">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header  text-white text-center " style="background-color: #196098;height: 50px;">
                    <h2 class="mb-0" style="color:#fff;size: 30px;font-weight:bold">تسجيل الدخول</h2>
                </div>
                <div class="card-body p-4">
                    <!-- رسالة الحالة -->
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- نموذج تسجيل الدخول -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- البريد الإلكتروني -->
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" id="email" name="email" class="form-control rounded-3" placeholder="أدخل بريدك الإلكتروني" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- كلمة المرور -->
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" id="password" name="password" class="form-control rounded-3" placeholder="أدخل كلمة المرور" required>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- تذكرني -->
                        <div class="form-check mb-3">
                            <input type="checkbox" id="remember_me" name="remember" class="form-check-input">
                            <label for="remember_me" class="form-check-label">تذكرني</label>
                        </div>

                        <!-- زر تسجيل الدخول ورابط نسيت كلمة المرور -->
                        <div class="d-flex justify-content-between align-items-center">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-primary small">نسيت كلمة المرور؟</a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-3 rounded-pill" style="background-color: #196098;">تسجيل الدخول</button>
                    </form>
                </div>
                <div class="card-footer text-center bg-light rounded-bottom">
                    <p class="mb-0">لا تمتلك حسابًا؟ <a href="{{ route('register') }}" class="text-primary">سجل الآن</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
