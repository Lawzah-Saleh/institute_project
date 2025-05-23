<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <!-- شعار أو صورة المعهد -->
            <div class="sidebar-header" >
                <img src="{{ asset('admin/assets/img/logo.png') }}" alt="اسم المعهد" style="width: 200px; height: auto; max-height: 120px; margin: 0 auto; display: block; border-radius: 10%; background-color: rgba(255, 255, 255, 0.9);box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            </div>
            <ul>
                <li class="menu-title">
               <br/>
                </li>
                <li><a href="{{ route('admin.dashboard') }}"><i class="feather-grid"></i> <span> وحدة التحكم </span></a></li>
                <li class="submenu">
                    <a href="#"><i class="fas fa-graduation-cap"></i> <span>الطلاب</span><span
                        class="menu-arrow"></span</a>
                    <ul>
                        <li><a href="{{ route('students.index') }}">عرض الطلاب</a></li>

                        <li><a href="{{ route('students.create') }}">إضافة طالب</a></li>
                        <li><a href="{{ route('students.register_next_course_form')  }}">تحديث الطالب للدورة التالية </a></li>

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
                        <li><a href="{{ route('attendance.index') }}"> قائمة الحضور </a></li>
                        <li><a href="{{ route('attendance.create') }}"> أضافة الحضور </a></li>
                        <li><a href="{{ route('attendance.report') }}"> تقرير الحضور </a></li>
                        <li><a href="{{ route('attendance.monthly_report') }}"> شهري تقرير الحضور </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#"><i class="fas fa-calendar-check"></i> <span> إدارة الدرجات </span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('degrees.index') }}"> قائمة الدرجات </a></li>
                        <li><a href="{{ route('degrees.create') }}"> أضافة الدرجات </a></li>

                    </ul>
                </li>



                <li class="submenu">
                    <a href="#"><i class="fas fa-clipboard"></i> <span>إدارة جهات السداد </span> <span
                        class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('payment_sources.index') }}">جهات السداد</a></li>
                            <li><a href="{{ route('payment_sources.create') }}">إضافة جهة سداد</a></li>
                        </ul>
                </li>
                <li class="submenu">
                    <a href="#"><i class="fas fa-comment-dollar"></i> <span>أدارة رسوم الطلاب</span><span
                         class="menu-arrow"></span></a>

                    <ul>
                        <li><a href="{{ route('admin.payments.index') }}"> قائمة الرسوم </a></li>
                        <li><a href="{{ route('admin.payments.create') }}">إضافة رسوم</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#"><i class="fa fa-newspaper"></i> <span> التقارير</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.reports.filtered_paid_students') }}">تقرير الطلاب المدفوعة</a></li>
                        <li><a href="{{ route('admin.reports.financial_status_search') }}">بيان حالة مالية للطالب </a></li>
                        <li><a href="{{ route('admin.reports.payment_summary') }}">كشف بالمبالغ المسددة  </a></li>
                        <li><a href="{{ route('admin.reports.payment_budget_report') }}">ميزانية الدفع  </a></li>
                        <li><a href="{{ route('admin.reports.payment_statement_report') }}">المبالغ المسددة بين تاريخيين    </a></li>
                        <li><a href="{{ route('admin.reports.students_in_course_report') }}">  كشف بيانات الطلاب بالدورات      </a></li>
                        <li><a href="{{ route('admin.reports.students_grades_report') }}">  كشف درجات الطلاب بالدورات      </a></li>
                        <li><a href="{{ route('admin.reports.student_grade_search') }}">  بيان درجة طالب       </a></li>
                        <li><a href="{{ route('admin.reports.courses_report') }}">  كشف بالدورات المتاحة   </a></li>
                        <li><a href="{{ route('admin.reports.teachers_in_courses') }}">  كشف بالمدرسين  للدورات   </a></li>
                        <li><a href="{{ route('admin.reports.courses_on_date') }}">     تقرير بالدورات المقامة في تاريخ   </a></li>
                        <li><a href="{{ route('admin.reports.courses_status') }}">     تقرير بالدورات المنتهية وغير المنتهيئة   </a></li>
                        <li><a href="{{ route('admin.reports.attendance_report') }}">     تقارير الحضور والغياب    </a></li>
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
                    <a href="#"><i class="fa fa-newspaper"></i> <span> اعدادات الصفحة الرئيسية</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('institute.create') }}">أضافة معلومات المعهد</a></li>
                        <li><a href="{{ route('institute.index') }}">عرض معلومات المعهد</a></li>
                    </ul>
                </li>
                <li class="">
                    <a href="{{ route('student.search') }}"><i class="fa fa-newspaper"></i> <span> أصدار شهادة </span>
                        <span ></span>
                    </a>
 
                </li>
            </ul>
        </div>
    </div>
</div>
