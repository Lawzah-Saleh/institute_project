@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
    }

    .login-wrapper {
        min-height: 100vh;
        background: url('/assets/img/hero-bg-light.webp') center center / cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .login-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(6px);
        z-index: 1;
    }

    .login-content {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 450px;
    }

    .card.login-card {
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 1.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .login-header {
        background-color: #196098;
        border-top-left-radius: 1.5rem;
        border-top-right-radius: 1.5rem;
        padding: 1rem;
        text-align: center;
    }

    .login-header h2 {
        color: #fff;
        font-weight: bold;
        margin: 0;
    }

    .btn-login {
        background-color: #196098;
        border-radius: 50px;
    }

    .btn-login:hover {
        background-color: #154d7b;
    }
</style>

<div class="login-wrapper">
    <div class="login-overlay"></div>

    <div class="login-content">
        <div class="card login-card">
            <div class="login-header">
                <h2>تسجيل الدخول</h2>
            </div>
            <div class="card-body p-4">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="أدخل بريدك الإلكتروني">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input type="password" id="password" name="password" class="form-control" required placeholder="أدخل كلمة المرور">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" id="remember_me" name="remember" class="form-check-input">
                        <label for="remember_me" class="form-check-label">تذكرني</label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-primary small">نسيت كلمة المرور؟</a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-login">تسجيل الدخول</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
