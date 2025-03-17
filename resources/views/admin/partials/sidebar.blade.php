<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <!-- شعار أو صورة المعهد -->
            <div class="sidebar-header" >
                <img src="{{ asset('admin/assets/img/logo.png') }}" alt="اسم المعهد" style="width: 200px; height: auto; max-height: 120px; margin: 0 auto; display: block; border-radius: 10%; background-color: rgba(255, 255, 255, 0.9);box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            </div>
            <ul>
                <li><a href="{{ route('dashboard') }}"><i class="feather-grid"></i> <span> وحدة التحكم </span></a></li>
                <li class="submenu">
                    <a href="#"><i class="fas fa-graduation-cap"></i> <span>الطلاب</span> <span
                        class="menu-arrow"></span</a>
                    <ul>
                        <li><a href="{{ route('students.index') }}">عرض الطلاب</a></li>

                        <li><a href="{{ route('students.create') }}">إضافة طالب</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#"><i class="fas fa-chalkboard-teacher"></i> <span>الموظفين</span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ url('employees') }}">عرض الموظفين</a></li>
                        <li><a href="{{ url('employees/create') }}">إضافة موظف</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#"><i class="fas fa-building"></i> <span> الأقسام</span> <span
                            class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="{{ route('departments.index') }}"> عرض الاقسام</a></li>
                                <li><a href="{{ route('departments.create') }}"> أضافة قسم</a></li>
                            </ul>

                </li>
                <li class="submenu">
                    <a href="#"><i class="fas fa-book-reader"></i> <span> الدورات</span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('courses.index')}}">عرض الدورات</a></li>
                        <li><a href="{{ route('courses.create')}}">اضافة دورة </a></li>
                        <li><a href="{{ route('course-sessions.index')}}">عرض الدورات المتاحة</a></li>
                        <li><a href="{{ route('course-sessions.create')}}">اضافة دورة متاحة </a></li>
                        <li><a href="{{ route('course-prices.index')}}">عرض الأسعار</a></li>
                        <li><a href="{{ route('course-prices.create')}}">اضافة سعر </a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#"><i class="fas fa-building"></i> <span> أيام الاجازة</span> <span
                            class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="{{ route('holidays.index') }}"> عرض أيام الاجازة</a></li>
                                <li><a href="{{ route('holidays.create') }}"> أضافة يوم أجازة</a></li>
                            </ul>

                </li>
                <li class="submenu">
                    <a href="#"><i class="fas fa-calendar-check"></i> <span> إدارة الحضور </span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('admin.attendance.index') }}"> قائمة الحضور </a></li>
                        <li><a href="{{ route('attendance.report') }}"> تقرير الحضور </a></li>
                        <li><a href="{{ route('attendance.monthly_report') }}"> شهري تقرير الحضور </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#"><i class="fas fa-calendar-check"></i> <span> إدارة الدرجات </span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('degrees.index') }}"> قائمة الدرجات </a></li>
                        <li><a href="{{ route('degrees.create') }}"> أضافة الدرجات </a></li>
                        {{-- <li><a href="{{ route('attendance.report') }}"> تقرير الحضور </a></li>
                        <li><a href="{{ route('attendance.monthly_report') }}"> شهري تقرير الحضور </a></li> --}}
                    </ul>
                </li>





                <li class="submenu">
                    <a href="#"><i class="fa fa-newspaper"></i> <span> اعدادات الصفحة الرئيسية</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('institute.create') }}">أضافة معلومات المعهد</a></li>
                        <li><a href="{{ route('institute.index') }}">عرض معلومات المعهد</a></li>
                        {{-- <li><a href="{{ route('institute.edit') }}">تعديل معلومات المعهد</a></li> --}}
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#"><i class="fa fa-newspaper"></i> <span> الاعلانات</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('advertisements.index') }}">كل الإعلانات</a></li>
                        <li><a href="{{ route('advertisements.create') }}">إضافة إعلان</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#"><i class="fas fa-clipboard"></i> <span>المالية</span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        {{-- <li><a href="invoices.html">Invoices List</a></li>
                        <li><a href="add-invoice.html">Add Invoices</a></li>
                        <li><a href="edit-invoice.html">Edit Invoices</a></li>
                        <li><a href="view-invoice.html">Invoices Details</a></li>
                        <li><a href="invoices-settings.html">Invoices Settings</a></li> --}}
                    </ul>
                </li>

                <li>
                    <a href="fees.html"><i class="fas fa-comment-dollar"></i> <span>أدارة رسوم الطلاب</span></a>
                </li>

                <li>
                    <a href="add-fees.html"><i class="fas fa-clipboard-list"></i> <span>أضافة رسوم للطالب</span></a>
                </li>
                {{-- <li class="submenu">
                    <a href="#"><i class="fas fa-shield-alt"></i> <span> Authentication </span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="login.html">Login</a></li>
                        <li><a href="register.html">Register</a></li>
                        <li><a href="forgot-password.html">Forgot Password</a></li>
                        <li><a href="error-404.html">Error Page</a></li>
                    </ul>
                </li> --}}
                {{-- <li class="submenu">
                    <a href="#"><i class="fab fa-get-pocket"></i> <span>Base UI </span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="placeholders.html">Placeholders</a></li>
                        <li><a href="sweetalerts.html">Sweet Alerts</a></li>
                        <li><a href="toastr.html">Toasts</a></li>
                    </ul>--}}

            </ul>
        </div>
    </div>
</div>
