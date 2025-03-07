

{{-- ======= --}}
<div class="sidebar" id="sidebar" style="background-color: #196098; " >
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <div class="sidebar-header" >
                <img src="{{ asset('student/assets/img/efi.jpg') }}" alt="اسم المعهد" style="width: 200px; height: auto; max-height: 120px; margin: 0 auto; display: block; border-radius: 10%; background-color: rgba(255, 255, 255, 0.9);box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            </div>

            <ul>
                <li class="nav-item dropdown has-arrow new-user-menus">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <span class="user-img">
                            <div class="" style="margin-top: 10px;">
                                {{-- <img class="rounded-circle img" src="{{ asset('student/assets/img/profiles/avatar-01.jpg') }}" --}}
                                <img class="rounded-circle img" src="{{ asset('student/assets/img/profiles/st.jpg') }}"
                                    style="width: 80% ;height: 80%;" alt="Soeng Souy">
                            </div>
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="user-header">
                            <div class="avatar avatar-sm">
                                <img src="{{ asset('student/assets/img/profiles/st.jpg') }}" alt="User Image"
                                    class="avatar-img rounded-circle">
                            </div>
                            <div class="user-text">
                                <h6>Farah</h6>
                                <p class="text-muted mb-0">Student</p>
                            </div>
                        </div>
                        <li>
                            <li>
                                <a href="{{ url('/') }}" style="color: white;"><i class="feather-grid" style="color: white;"></i> <span>لوحة التحكم </span>
                                    <span></span>
                                </a>
                            </li>
                <li>
                    <a href="{{ url('degree') }}" style="color: white;"><i class="fas fa-file-alt" style="color:white"></i> <span> الدرجات</span>
                        <span></span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('attendance') }}" style="color: white;"><i class="fas fa-clipboard" style="color: white;"></i> <span> الحضور والغياب</span> <span></span>
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ url('add-result') }}" style="color: orange;"><i class="fas fa-clipboard-list" style="color: orange;"></i><span> اضافة الدرجات</span> <span></span>
                    </a>
                </li> --}}
                <li>
                    <a href="{{url('profile')}}" style="color: white;"><i class="fas fa-user-circle" style="font-size: 1.2rem;"></i><span> الملف الشخصي</span> <span></span>
                    </a>
                
                </li>
                <li>
                    <a href="{{ url('payment') }}" style="color: white;"><i class="fas fa-clipboard" style="color: white;"></i> <span> الدفع</span> <span></span>
                    </a>
                </li>
                {{-- <li>
                    <a href="{{url('courses')}}" style="color: orange;"><i class="fas fa-book-reader" style="color: orange;"></i> <span > المواد</span> <span></span>
                    </a>
                </li > --}}
            </ul>
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