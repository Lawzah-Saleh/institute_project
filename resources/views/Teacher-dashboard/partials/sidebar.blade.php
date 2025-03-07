<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">

            <div class="sidebar-header" style="padding-top: 20%" >
                <img src="{{ asset('Teacher/assets/img/efi(1).png') }}" alt="اسم المعهد" style="width: 200px; height: auto; max-height: 120px; margin: 0 auto; display: block; border-radius: 10%; background-color: rgba(255, 255, 255, 0.9);box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            </div>
            <span class="user-img">
                <div class="">
                <img class="rounded-circle img" src="{{ asset('Teacher/assets/img/profiles/profile-t.png') }}"
                style="width: 60% ;height: 60%;"
                    alt="Soeng Souy">
                </div>

             </span>
            <ul>
                <li class="nav-item dropdown has-arrow new-user-menus">
                        <li class="">


                            <li>

                                <a href="{{ url('/') }}"><i class="feather-grid"></i> <span>لوحة التحكم </span>
                                    <span></span>
                                </a>
                            </li>

                <li>

                    <a href="{{ url('students') }}"><i class="fas fa-graduation-cap"></i> <span> الطلاب</span>
                        <span></span>
                    </a>
                </li>

                <li>

                    <a href="{{ url('presence and absence') }}"><i class="fas fa-clipboard"></i> <span> الحضور والغياب</span> <span></span>

                    </a>
                </li>




                <li>
                    <a href="{{ url('add-result') }}"><i class="fas fa-clipboard-list"></i><span> اضافة الدرجات</span> <span></span>

                    </a>
                </li>

                <li>
                    <a href="{{url('profile')}}"><span> الملف الشخصي</span> <span></span>

                    </a>
                </li>


                <li>
                    <a href="{{url('courses')}}"><i class=" fas fa-book-reader"></i> <span > المواد</span> <span></span>

                    </a>
                </li >









            </ul>
            </li>
            </ul>
        </div>
    </div>
</div>

