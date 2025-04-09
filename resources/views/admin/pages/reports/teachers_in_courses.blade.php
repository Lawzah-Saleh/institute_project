@extends('admin.layouts.app')

@section('title', 'تقرير المدرسين للدورات')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <!-- العنوان -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تقرير المدرسين للدورات</h3>
                </div>
            </div>
        </div>

        <!-- فلاتر البحث -->
        <form method="GET" action="{{ route('admin.reports.teachers_in_courses') }}" class="mb-4">
            <div class="row">
                <!-- قسم -->
                <div class="col-md-4">
                    <label>اختر القسم:</label>
                    <select name="department_id" id="department_id" class="form-control">
                        <option value="">-- اختر القسم --</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- فلتر الوقت -->
                <div class="col-md-4">
                    <label>اختر الوقت:</label>
                    <select name="time_period" class="form-control">
                        <option value="">-- اختر الوقت --</option>
                        <option value="8-10" {{ request('time_period') == '8-10' ? 'selected' : '' }}>من 8 إلى 10</option>
                        <option value="10-12" {{ request('time_period') == '10-12' ? 'selected' : '' }}>من 10 إلى 12</option>
                        <option value="2-4" {{ request('time_period') == '2-4' ? 'selected' : '' }}>من 2 إلى 4</option>
                        <option value="4-6" {{ request('time_period') == '4-6' ? 'selected' : '' }}>من 4 إلى 6</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4 text-left">
                    <button type="submit" class="btn" style="background-color: #196098;color: white">بحث</button>
                </div>
            </div>
        </form>

        <!-- جدول التقرير -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">بيانات الدورات والمدرسين</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>اسم الدورة</th>
                            <th>اسم المدرس</th>
                            <th>الوقت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            @foreach($course->sessions as $session)
                                <tr>
                                    <td>{{ $course->course_name }}</td>
                                    <td>
                                        {{ $session->employee->name_ar ?? 'غير محدد' }}
                                    </td>
                                    <td>{{ $session->start_time ?? 'غير محدد' }} - {{ $session->end_time ?? 'غير محدد' }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">لا توجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- زر التصدير -->
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{ route('admin.reports.export_excel_teachers_in_courses', ['department_id' => request('department_id'), 'time_period' => request('time_period')]) }}" class="btn" style="background-color: #e94c21;color: white">
                    تصدير إلى Excel
                </a>
                <a href="{{ route('admin.reports.export_pdf_teachers_in_courses', ['department_id' => request('department_id'), 'time_period' => request('time_period')]) }}" class="btn" style="background-color: #e94c21;color: white">
                    تصدير إلى PDF
                </a>
            </div>
        </div>

    </div>
</div>

<script>
    // يمكنك إضافة كود جافا سكربت أو jQuery هنا إذا كنت بحاجة لتصفية أو إضافة تأثيرات تفاعلية
</script>

@endsection
