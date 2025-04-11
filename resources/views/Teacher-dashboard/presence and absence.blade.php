@extends('Teacher-dashboard.layouts.app')

@section('title', 'presence and absence')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid" style="background-color: #f9f9fb">

        <!-- ترحيب بالمعلم -->
        <div class="flex justify-center mb-6">
            <div class="bg-white shadow-md rounded-lg p-6 text-center w-full md:w-2/3">
                <h3 class="text-2xl font-bold text-gray-700" > التـحضير </h3>
            </div>
        </div>




        @if(session('error'))
            <div class="alert alert-danger " style="background-color: #e2e8f0; color: #000; margin-top: 20px;">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success"  style="background-color: #e2e8f0; color: #000; margin-top: 20px;">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="إغلاق" onclick="closeAlert()"
                style="float: left; background: none; border: none; font-size: 1.5rem;">
            &times;
        </button>
            </div>
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






<!-- حقل اختيار التاريخ -->
<div class="col-sm-6">
    <div class="form-group">
        <label>اختيار التاريخ</label>
        <input type="date" id="attendance_date" name="attendance_date" class="form-control" placeholder="اختر التاريخ" readonly>
    </div>
</div>





                <div class="col-lg-6 col-md-12 mb-3">
                    <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="width: 200%; height: 40px; background: #e94c21; font-size: 1.1rem;">عرض الطلاب</button>
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
                        {{-- <th>تاريخ اليوم</th> --}}
                        <th>التحضير</th>
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








                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" style="transform: scale(1.2);">

                     </td>


                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>



            <button type="submit" class="btn btn-success"  style="width: 20%; height: 40px; background: #196098; font-size: 1.1rem;">حفظ التحضير</button>
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
            form.action = `/presence and absence/${sessionId}`;
        }
    });
});

$(document).ready(function () {
    @if ($session)
        var startDate = "{{ $session->start_date }}";
        var endDate = "{{ $session->end_date }}";
    @else
        var startDate = null;
        var endDate = null;
    @endif

        var holidays = @json($holidays); // تخزين الإجازات في مصفوفة JavaScript من الـ Backend

        var holidayDates = holidays.map(function (holiday) {
            return holiday.date; // استرجاع التواريخ من الإجازات
        });

        // تهيئة تقويم التاريخ
        $("#attendance_date").datepicker({
            dateFormat: 'yy-mm-dd', // تنسيق التاريخ
            minDate: startDate, // التاريخ الأدنى لاختيار التاريخ (بداية الجلسة)
            maxDate: endDate,   // التاريخ الأقصى لاختيار التاريخ (نهاية الجلسة)
            beforeShowDay: function (date) {
                var dateString = $.datepicker.formatDate('yy-mm-dd', date);
                // منع اختيار أيام الجمعة (اليوم 5)
                if (date.getDay() == 5) {
                    return [false];
                }
                // منع اختيار أيام الإجازات
                if (holidayDates.indexOf(dateString) !== -1) {
                    return [false];
                }
                return [true];
            },
            onSelect: function(dateText) {
                // عند اختيار تاريخ، نقوم بتحديثه في الحقل المخفي أو نعرضه للمستخدم
                $('#attendance_date').val(dateText);
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

























    </script>







@endsection
