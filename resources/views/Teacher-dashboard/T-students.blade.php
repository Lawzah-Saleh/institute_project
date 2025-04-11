

@extends('Teacher-dashboard.layouts.app')

@section('title', 'عرض الطلاب')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid" style="background-color: #f9f9fb">

        <!-- ترحيب بالمعلم -->
        <div class="flex justify-center mb-6">
            <div class="bg-white shadow-md rounded-lg p-6 text-center w-full md:w-2/3">
                <h3 class="text-2xl font-bold text-gray-700">عرض الطلاب</h3>
            </div>
        </div>




        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif





@if(isset($noStudentsMessage) && $noStudentsMessage)
    <div id="no-students-alert" class="alert alert-warning alert-dismissible fade show" role="alert"
         style="background-color: #e2e8f0; color: #000; margin-top: 20px;">
        {{ $noStudentsMessage }}
        <button type="button" class="close" data-dismiss="alert" aria-label="إغلاق" onclick="closeAlert()"
                style="float: left; background: none; border: none; font-size: 1.5rem;">
            &times;
        </button>
    </div>
@endif








        <!-- نموذج اختيار القسم والكورس والجلسة -->
        <form method="GET" id="degreeForm">
            <div class="student-group-form mb-4">
                <div class="row">


                <div class="col-lg-6 col-md-12 mb-3">
                    <div class="form-group">

                    <select name="department_id" id="department_id" class="form-control">
                        <option value="">-- اختر القسم --</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>



            <div class="col-lg-6 col-md-12 mb-3">
                <div class="form-group">
                    <select name="course_id" id="course_id" class="form-control" {{ request('department_id') ? '' : 'disabled' }}>
                        <option value="">-- اختر الكورس --</option>
                        @foreach(!empty($courses) ? $courses : [] as $course)

                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->course_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                </div>

                <div class="col-lg-6 col-md-12 mb-3">
                    <div class="form-group">
                    <select name="session_id" id="session_id" class="form-control" {{ request('course_id') ? '' : 'disabled' }}>
                        <option value="">-- اختر الجلسة --</option>
                        @foreach(!empty($sessions) ? $sessions : [] as $session)

                            <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>
                                {{ $session->start_date }} - {{ $session->end_date }}
                            </option>
                        @endforeach
                    </select>
                </div>
                </div>





                <div class="col-lg-6 col-md-12 mb-3">
                    <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="width: 100%; height: 40px; background: #e94c21; font-size: 1.1rem;">عرض الطلاب</button>
                </div>
            </div>


              <!-- البحث بالاسم -->
              <div class="col-lg-6 col-md-12 mb-3">
                <div class="form-group">
                    <input type="text" id="search-name" class="form-control" style="width: 100%; height: 45px;" placeholder="البحث بالاسم...">
                </div>
            </div>

            <!-- زر عرض الطلاب -->
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="width: 100%; height: 40px; background: #e94c21; font-size: 1.1rem;">عرض الطلاب</button>
                </div>
            </div>




            </div>
        </div>
        </form>



        <!-- جدول الطلاب ودرجاتهم -->
        @if(request()->has('session_id') && isset($students) && count($students))


        <form action="{{ route('degrees.store') }}" method="POST">
            @csrf
            <input type="hidden" name="session_id" value="{{ $session->id }}">
            <div class="table-responsive">
                <table class="table border-0 table-hover table-center mb-0 datatable">
                    <thead class="text-white" style="background-color: #196098;">
                    <tr>
                        <th>الرقم</th>
                        <th>اسم الطالب</th>
                        <th>اسم الدورة</th>
                        <th>تاريخ الجلسة (من - إلى)</th>
                        <th>وقت الجلسة (من - إلى)</th>
                        <th>الدرجة العملية</th>
                        <th>الدرجة النهائية</th>
                        <th>درجة الحضور</th>
                        <th>المجموع</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody id="students-table-body">
                    @foreach ($students as $index => $student)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->student_name_ar }}</td>
                        <td>
                            @if($student->courseSessions->isNotEmpty())
                                {{ $student->courseSessions->first()->course->course_name ?? 'غير محدد' }}
                            @else
                                غير محدد
                            @endif
                        </td>
                        <td>
                            @if($student->courseSessions->isNotEmpty())
                                {{ $student->courseSessions->first()->start_date}} - {{ $student->courseSessions->first()->end_date }}
                            @else
                                غير محدد
                            @endif
                        </td>

                        <td>
                            @if($student->courseSessions->isNotEmpty())
                                {{ $student->courseSessions->first()->start_time}} - {{ $student->courseSessions->first()->end_time }}
                            @else
                                غير محدد
                            @endif
                        </td>
                        @php $degree = $student->degrees->first(); @endphp

                        @if($degree)
                            <td>{{ $degree->practical_degree }}</td>
                            <td>{{ $degree->final_degree }}</td>
                            <td>{{ $degree->attendance_degree }}</td>
                            <td>{{ $degree->total_degree }}</td>
                            <td>{{ $degree->total_degree >= 50 ? 'ناجح' : 'راسب' }}</td>
                        @else
                            <td colspan="5">لا توجد درجات لهذا الطالب</td>
                        @endif
                        

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>




        </form>
    @endif
    </div>
</div>
@endsection


@section('scripts')


<script>


document.addEventListener('DOMContentLoaded', function () {
    const departmentSelect = document.getElementById('department_id');
    const courseSelect = document.getElementById('course_id');
    const sessionSelect = document.getElementById('session_id');
    const form = document.getElementById('degreeForm');

    // Load courses on department change
    departmentSelect.addEventListener('change', function () {
        const departmentId = this.value;
        courseSelect.innerHTML = '<option>جاري التحميل...</option>';
        sessionSelect.innerHTML = '<option>-- اختر الجلسة --</option>';
        courseSelect.disabled = true;
        sessionSelect.disabled = true;

        if (departmentId) {
            fetch(`/teacher/get-courses/${departmentId}`)
                .then(res => res.json())
                .then(data => {
                    courseSelect.innerHTML = '<option value="">-- اختر الكورس --</option>';
                    data.forEach(course => {
                        const option = new Option(course.course_name, course.id);
                        courseSelect.add(option);
                    });
                    courseSelect.disabled = false;
                });
        }
    });

    // Load sessions on course change
    courseSelect.addEventListener('change', function () {
        const courseId = this.value;
        sessionSelect.innerHTML = '<option>جاري التحميل...</option>';
        sessionSelect.disabled = true;

        if (courseId) {
            fetch(`/get-sessions/${courseId}`)
                .then(res => res.json())
                .then(data => {
                    sessionSelect.innerHTML = '<option value="">-- اختر الجلسة --</option>';
                    data.forEach(session => {
                        const option = new Option(session.start_date + ' - ' + session.end_date, session.id);
                        sessionSelect.add(option);
                    });
                    sessionSelect.disabled = false;
                });
        }
    });

    // Update form action on session selection
    sessionSelect.addEventListener('change', function () {
        const sessionId = this.value;
        if (sessionId) {
            form.action = `/T-students/${sessionId}`;
        }
    });
});



</script>





<script>
    document.addEventListener("DOMContentLoaded", function () {
        const alertBox = document.getElementById("no-students-alert");

        // إخفاء تلقائي بعد 3 ثوانٍ
        if (alertBox) {
            setTimeout(() => {
                fadeOutAlert(alertBox);
            }, 3000);
        }
    });

    function closeAlert() {
        const alertBox = document.getElementById("no-students-alert");
        if (alertBox) {
            fadeOutAlert(alertBox);
        }
    }

    function fadeOutAlert(element) {
        element.style.transition = "opacity 1s ease";
        element.style.opacity = 0;
        setTimeout(() => {
            element.style.display = "none";
        }, 1000);
    }
</script>



<script>

// البحث عند الضغط على زر البحث
document.addEventListener('DOMContentLoaded', function () {
    const searchButton = document.getElementById('search-button');
    const searchNameInput = document.getElementById('search-name');
    const departmentSelect = document.getElementById('department_id');
    const courseSelect = document.getElementById('course_id');
    const sessionSelect = document.getElementById('session_id');

    // البحث عند الضغط على زر البحث
    searchButton.addEventListener('click', function () {
        const searchQuery = searchNameInput.value.trim();
        const departmentId = departmentSelect.value;
        const courseId = courseSelect.value;
        const sessionId = sessionSelect.value;

        if (searchQuery && departmentId && courseId && sessionId) {
            fetch(`/search-students?name=${searchQuery}&department_id=${departmentId}&course_id=${courseId}&session_id=${sessionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        displayStudents(data); // عرض الطلاب في الجدول
                    } else {
                        alert('لا يوجد طلاب بهذا الاسم.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching students:', error);
                });
        } else {
            alert("يرجى اختيار القسم والكورس والجلسة قبل البحث.");
        }
    });

    // دالة لعرض الطلاب في الجدول
    function displayStudents(students) {
        const tableBody = document.querySelector('#students-table');
        tableBody.innerHTML = ''; // تفريغ الجدول الحالي

        students.forEach((student, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${student.student_name_ar}</td>
                <td>${student.course ? student.course.course_name : 'غير محدد'}</td>
                <td>${student.session_time ? student.session_time : 'غير محدد'}</td>
                <td>${student.practical_degree}</td>
                <td>${student.final_degree}</td>
                <td>${student.attendance_degree}</td>
                <td>${student.total_degree}</td>
                <td>${student.total_degree >= 50 ? 'ناجح' : 'راسب'}</td>
            `;
            tableBody.appendChild(row);
        });
    }
});


</script>




@endsection
