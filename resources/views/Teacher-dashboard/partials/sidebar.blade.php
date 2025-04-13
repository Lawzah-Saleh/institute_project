<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">

            <!-- شعار المعهد -->
            <div class="sidebar-header" style="padding-top: 20%">
                <img src="{{ asset('Teacher/assets/img/efi(1).png') }}" alt="اسم المعهد" style="width: 200px; height: auto; max-height: 120px; margin: 0 auto; display: block; border-radius: 10%; background-color: rgba(255, 255, 255, 0.9); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            </div>

            <!-- صورة الملف الشخصي -->
            <span class="user-img">
                <div class="">
                    @php
                    use Illuminate\Support\Facades\Auth;

                    $employee = Auth::user()->employee;

                    $profileImage = $employee && $employee->image && file_exists(public_path('storage/' . $employee->image))
                        ? asset('storage/' . $employee->image)
                        : asset('Teacher/assets/img/profile-t.png');
                    @endphp

                    <img class="rounded-circle"
                         src="{{ $profileImage }}"
                         alt="Profile"
                         style="width: 150px; height: 150px; object-fit: cover; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.2); margin-top: 20px;">
                </div>
            </span>

            <!-- ترحيب بالمعلم -->
            <div class="text-center mt-3 text-white">
                <h3 class="text-xl font-semibold  text-white">مرحبًا أستاذ {{ $employee->name_ar }}</h3>
                <p class="text-sm">أنت الآن في لوحة التحكم الخاصة بك</p>
            </div>

            <ul>
                <!-- إضافة روابط السيديبار -->
                <li>
                    <a href="{{ url('/teacher/dashboard') }}">
                        <i class="feather-grid"></i>
                        <span class="text-white">لوحة التحكم</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('T-students') }}">
                        <i class="fas fa-graduation-cap"></i>
                        <span class="text-white">الطلاب</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('teacher.attendance.form') }}">
                        <i class="fas fa-clipboard"></i>
                        <span class="text-white">الحضور والغياب</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('add-result') }}">
                        <i class="fas fa-clipboard-list"></i>
                        <span class="text-white">اضافة الدرجات</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('T-profile') }}">
                        <i class="fas fa-user"></i>
                        <span class="text-white">الملف الشخصي</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('T-courses') }}">
                        <i class="fas fa-book-reader"></i>
                        <span class="text-white">الدورات</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
