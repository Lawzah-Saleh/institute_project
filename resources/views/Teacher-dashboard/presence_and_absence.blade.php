@extends('Teacher-dashboard.layouts.app')

@section('title', 'الحضور والغياب')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid" style="background-color: #f9f9fb">

        <!-- ترحيب بالمعلم -->
        <div class="flex justify-center mb-6">
            <div class="bg-white shadow-md rounded-lg p-6 text-center w-full md:w-2/3">
                <h3 class="text-2xl font-bold text-gray-700">إدارة الحضور</h3>
            </div>
        </div>

        <!-- رسائل التنبيه -->
        @if(session('error'))
            <div class="alert alert-danger" style="background-color: #e2e8f0; color: #000; margin-top: 20px;">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success" style="background-color: #e2e8f0; color: #000; margin-top: 20px;">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="إغلاق" onclick="closeAlert()"
                        style="float: left; background: none; border: none; font-size: 1.5rem;">&times;</button>
            </div>
        @endif

        <!-- نموذج اختيار القسم، الدورة، الجلسة، وتاريخ الجلسة -->
        <form method="GET" id="degreeForm">
            <div class="student-group-form mb-4">
                <div class="row">
                    <!-- اختيار القسم -->
                    <div class="col-lg-6 col-md-12 mb-3">
                        <div class="form-group">
                            <select name="department_id" id="department_id" class="form-control" onchange="this.form.submit()">
                                <option value="">-- اختر القسم --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- اختيار الدورة -->
                    <div class="col-lg-6 col-md-12 mb-3">
                        <div class="form-group">
                            <select name="course_id" id="course_id" class="form-control" {{ request('department_id') ? '' : 'disabled' }} onchange="this.form.submit()">
                                <option value="">-- اختر الدورة --</option>
                                @foreach($courses ?? [] as $course)
                                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- اختيار الجلسة -->
                    <div class="col-lg-6 col-md-12 mb-3">
                        <div class="form-group">
                            <select name="session_id" id="session_id" class="form-control" {{ request('course_id') ? '' : 'disabled' }} onchange="this.form.submit()">
                                <option value="">-- اختر الجلسة --</option>
                                @foreach($sessions ?? [] as $session)
                                    <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>
                                        {{ $session->start_date }} - {{ $session->end_date }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

    <!-- نموذج اختيار تاريخ الجلسة -->
    @if(request()->has('session_id'))
        <form method="GET" action="{{ route('teacher.attendance.form') }}">
            <div class="form-group">
                <label for="session_date">اختيار تاريخ الجلسة</label>
                <select id="session_date" name="session_date" class="form-control" onchange="this.form.submit()">
                    <option value="">-- اختر تاريخ الجلسة --</option>
                    @foreach($sessionDates as $date)
                        <option value="{{ $date }}" {{ request('session_date') == $date ? 'selected' : '' }}>
                            {{ $date }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    @endif


   
        <!-- عرض الطلاب -->
        @if(request()->has('session_date') && isset($students) && count($students))
            <form method="POST" action="{{ route('teacher.attendance.store') }}">
                @csrf
                <input type="hidden" name="session_id" value="{{ request('session_id') }}">
                <input type="hidden" name="session_date" value="{{ request('session_date') }}">
                <div class="table-responsive">
                    <table class="table border-0 table-hover table-center mb-0 datatable">
                        <thead class="text-white" style="background-color: #196098;">
                            <tr>
                                <th>الرقم</th>
                                <th>اسم الطالب</th>
                                <th>اسم الدورة</th>
                                <th>تاريخ الجلسة</th>
                                <th>التحضير</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student->student_name_ar }}</td>
                                    <td>{{ $student->courseSessions->first()->course->course_name ?? 'غير محدد' }}</td>
                                    <td>{{ request('session_date') }}</td>
                                    <td class="text-center">
                                        <!-- Radio button for حضر / غائب -->
                                        <input type="radio" name="status[{{ $student->id }}]" value="1" {{ old('status.' . $student->id) == 1 ? 'checked' : '' }}> حاضر
                                        <input type="radio" name="status[{{ $student->id }}]" value="0" {{ old('status.' . $student->id) == 0 ? 'checked' : '' }}> غائب
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="btn btn-success" style="width: 100%; background: #196098; font-size: 1.1rem;">حفظ التحضير</button>
            </form>
        @endif
    </div>
</div>

@endsection
