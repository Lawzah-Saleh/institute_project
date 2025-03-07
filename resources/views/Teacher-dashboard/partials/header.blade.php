<div class="header" style="direction: rtl; background-color: #196098; width: 100vw; padding: 10px 20px;height:auto;">
    <div class="header-right" style="background-color: #196098; ">

    </div>

    <!-- Student Menu -->
    <ul class="nav user-menu">


        @auth
        <!-- Authenticated Student Links -->
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
                {{-- <a href="{{ route('student/dashboard') }}" class="dropdown-item">لوحة الطالب</a> --}}
                <li><a href="
                    {{-- {{ route('student.profile') }} --}}
                     " class="dropdown-item">الملف الشخصي</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">تسجيل الخروج</button>
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
