<div class="header" style="direction: rtl; background-color: #196098; width: 100vw;height: 62px; padding: 10px 20px; display: flex; align-items: center; justify-content: space-between;">
    <div class="header-right" style="background-color: #196098;"></div>

    <!-- Student Menu -->
    <ul class="nav user-menu" style="display: flex; align-items: center; gap: 15px; list-style: none; padding: 0; margin: 0;">

        <!-- Maximize Icon (Updated to Match Student Icon Size) -->
        <li class="nav-item zoom-screen" style="display: flex; align-items: center;">
            <a href="#" class="nav-link header-nav-list win-maximize" style="display: flex; align-items: center;">
                <img src="{{ asset('student/assets/img/icons/header-icon-04.svg') }}" alt=""
                     style="width: 40px; height: 40px; border-radius: 50%; padding: 5px; background-color: #f2f2f2;">
            </a>
        </li>

         <!-- Notification Icon -->

        </li>
        <li class="nav-item notification-icon" style="position: relative; display: flex; align-items: center;">
            <a href="{{ route('student.notifications') }}"  class="nav-link header-nav-list" style="display: flex; align-items: center;">
                <div style="width: 40px; height: 40px; background-color: #f2f2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: background-color 0.2s ease-in-out;">
                    <i class="fas fa-bell" style="font-size: 20px; color: #196098; transition: transform 0.2s ease-in-out;"></i>
                </div>

            </a>
        </li>

        @auth
        <!-- Authenticated Student Links -->
        <li class="dropdown" style="display: flex; align-items: center;">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" style="display: flex; align-items: center; text-decoration: none; color: #f2f2f2;padding-bottom: 12px;">
                <span class="user-img" style="display: flex; align-items: center; gap: 10px;">
                    <div class="user-text">
                        {{ Auth::user()->name }} <i class="bi bi-chevron-down"></i>
                    </div>
                    <img class="rounded-circle" src="{{ Auth::user()->profile_photo_url ?? asset('default_profile.png') }}"
                         alt="الصورة الشخصية" style="width: 40px; height: 40px; border-radius: 50%;">
                </span>
            </a>

            <ul class="dropdown-menu text-end" style="min-width: 180px;">

                <li>
                    <a href=" {{-- {{ route('student.profile') }} --}}" style="background:#fffbfb""   class="dropdown-item">
                        <i class="fas fa-user-circle me-2" style="font-size: 1.2rem; margin-left: 10px;color: #196098"></i>  <span>الملف الشخصي</span></a></li>
                <li>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item" style="background:#fffbfb" > <i class="fas fa-sign-out-alt me-2" style="font-size: 1.2rem; margin-left: 10px;color: #196098"></i>
                             <span>تسجيل الخروج</span></button>
                    </form>
                </li>
            </ul>


        </li>
        @else
        <!-- Guest Links -->
        <li>
            <a class="btn-getstarted text-align-center" href="{{ route('login') }}"
               style="text-decoration: none; color: white; padding: 5px 10px; border: 1px solid white; border-radius: 5px;">تسجيل الدخول</a>
        </li>
        @if (Route::has('register'))
            <li>
                <a href="{{ route('register') }}"
                   style="text-decoration: none; color: white; padding: 5px 10px; border: 1px solid white; border-radius: 5px;">التسجيل</a>
            </li>
        @endif
        @endauth
    </ul>
</div>
