<div class="header" style="direction: rtl;">
    <div class="header-right">
        <!-- Logo Section -->
        <a href="{{ url('/') }}" class="logo">
            <img src="{{ asset('admin/assets/img/logo.png') }}" alt="Logo">
        </a>
        <a href="{{ url('/') }}" class="logo logo-small">
            <img src="{{ asset('admin/assets/img/logo-small.png') }}" alt="Logo" width="30" height="30">
        </a>
    </div>

    <!-- User Menu -->
    <ul class="nav user-menu">
        <li class="nav-item zoom-screen me-2">
            <a href="#" class="nav-link header-nav-list win-maximize">
                <img src="{{ asset('admin/assets/img/icons/header-icon-04.svg') }}" alt="">
            </a>
        </li>

        @auth
        <!-- Authenticated User Links -->
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                <span class="user-img">
                    <div class="user-text">
                        {{ Auth::user()->name }} <i class="bi bi-chevron-down"></i>
                    </div>
                    <img class="rounded-circle" src="{{ Auth::user()->profile_photo_url ?? asset('default_profile.png') }}" alt="الصورة الشخصية" width="50" height="50">
                </span>
            </a>
            <ul class="dropdown-menu">
                {{-- <a href="{{ route('/admin/dashboard') }}" class="dropdown-item">Dashboard</a> --}}
                {{-- <li><a href="{{ route('profile.edit') }}" class="dropdown-item">Profile</a></li> --}}
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Log Out</button>
                    </form>
                </li>
            </ul>
        </li>
        @else
        <!-- Guest Links -->
        <li><a class="btn-getstarted text-align-center" href="{{ route('login') }}">تسجيل الدخول</a></li>
        @if (Route::has('register'))
            <li><a href="{{ route('register') }}">التسجيل</a></li>
        @endif
        @endauth
    </ul>
</div>
