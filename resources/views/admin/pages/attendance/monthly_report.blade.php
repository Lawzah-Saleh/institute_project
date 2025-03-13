@extends('admin.layouts.app')

@section('title', 'تقرير الحضور الشهري')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <h3 class="page-title">تقرير الحضور الشهري لشهر {{ $month }} - {{ $year }}</h3>
            <div class="card shadow-sm p-4">
                <form method="GET" action="{{ route('attendance.monthly_report') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="month" class="form-label">اختر الشهر:</label>
                        <select name="month" id="month" class="form-select">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>
                    </div>
        
                    <div class="col-md-3">
                        <label for="year" class="form-label">اختر السنة:</label>
                        <select name="year" id="year" class="form-select">
                            @for ($y = now()->year - 5; $y <= now()->year; $y++)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
        
                    <div class="col-md-3">
                        <label for="department_id" class="form-label">اختر القسم:</label>
                        <select name="department_id" id="department_id" class="form-select">
                            <option value="">كل الأقسام</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ $department->id == $departmentId ? 'selected' : '' }}>
                                    {{ $department->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
        
                    <div class="col-md-3">
                        <label for="course_id" class="form-label">اختر الكورس:</label>
                        <select name="course_id" id="course_id" class="form-select">
                            <option value="">كل الكورسات</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" {{ $course->id == $courseId ? 'selected' : '' }}>
                                    {{ $course->course_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
        
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> عرض التقرير</button>
                    </div>
                </form>
            </div>
        
            <div class="card shadow-sm mt-4 p-4">
                <h5 class="mb-3">📌 بيانات الحضور</h5>
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>📛 اسم الطالب</th>
                            <th>📚 اسم الكورس</th>
                            <th>📆 التاريخ</th>
                            <th>✅ الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->student->name }}</td>
                                <td>{{ $attendance->session->course->course_name }}</td>
                                <td>{{ $attendance->attendance_date }}</td>
                                <td>
                                    <span class="badge bg-{{ $attendance->status ? 'success' : 'danger' }}">
                                        {{ $attendance->status ? '✅ حاضر' : '❌ غائب' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
    </div>
</div>
@endsection
