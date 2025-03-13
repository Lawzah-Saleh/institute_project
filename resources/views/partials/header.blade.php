<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <!-- Logo Section -->
        <a href="{{ route('home') }}" class="logo d-flex align-items-center">
            <img src="{{ asset('assets/img/efi.png') }}" alt="">
            <h1 class="sitename ms-2">{{ $institute->institute_name ?? ' معهد التعليم أولاّ' }}</h1>
        </a>

        <!-- Navigation Menu -->
        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ route('home') }}#hero" class="{{ request()->routeIs('home') ? 'active' : '' }}">الصفحة الرئيسية</a></li>
                <li><a href="{{ route('home') }}#about">من نحن</a></li>
                <li><a href="{{ route('home') }}#features-details">الإعلانات</a></li>
                <li><a href="{{ route('home') }}#services">الكورسات</a></li>
                <li><a href="{{ route('home') }}#contact">اتصل بنا</a></li>
                @auth
                    <!-- Authenticated User Links -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }} <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            {{-- <li><a href="{{ route('dashboard') }}">Dashboard</a></li> --}}
                            {{-- <li><a href="{{ route('profile.edit') }}">Profile</a></li> --}}
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
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
    </div>
</header>
