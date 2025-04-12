<div class="sidebar" id="sidebar" style="background-color: #196098; " >
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <div class="sidebar-header" >
                <img src="{{ asset('student/assets/img/efi.jpg') }}" alt="اسم المعهد" style="width: 200px; height: auto; max-height: 120px; margin: 0 auto; display: block; border-radius: 10%; background-color: rgba(255, 255, 255, 0.9);box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            </div>

            <ul>
                <li class="nav-item dropdown has-arrow new-user-menus">
                    <span class="user-img">
                        <div class="" style="margin-top: 10px;">
                            <img class="rounded-circle img" src="{{ asset('photo_2025-04-12_02-23-08.jpg') }}"
                                    style="width: 80% ;height: 80%;" alt="Soeng Souy">
                        </div>
                    </span>
                </li>
                <li>
                <a href="{{ url('student/dashboard') }}" style="color: white;"><i class="feather-grid" style="color: white;"></i> <span>لوحة التحكم </span>
                    <span></span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('profile.student.show') }}" style="color: white;"><i class="fas fa-user-circle" style="font-size: 1.2rem;"></i><span> الملف الشخصي</span> <span></span>
                    </a>

                </li>

                <li>
                    <a href="{{ route('student.attendance') }}" style="color: white;"><i class="fas fa-clipboard" style="color: white;"></i> <span> الحضور والغياب</span> <span></span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('student.degrees') }}" style="color: white;"><i class="fas fa-file-alt" style="color:white"></i> <span> الدرجات</span>
                        <span></span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('student.payments') }}" style="color: white;"><i class="fas fa-clipboard" style="color: white;"></i> <span> الدفع</span> <span></span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>

<style>
    /* تأثير التمرير لعناصر الشريط الجانبي */
.sidebar-menu ul li a {
    /* display: block; */
    padding: 10px 15px;
    color: white; /* لون النص الأساسي */
    text-decoration: none; /* إزالة الخط تحت النص */
    transition: background-color 0.3s ease, color 0.3s ease;
}

.sidebar-menu ul li a:hover {
    background-color: #e94c21; /* لون الخلفية عند التمرير */
    color: #ffffff; /* لون النص عند التمرير */
    border-radius: 5px; /* لإضافة انحناء بسيط عند التمرير */
}

</style>
