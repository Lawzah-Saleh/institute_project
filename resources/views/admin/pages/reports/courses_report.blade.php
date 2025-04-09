@extends('admin.layouts.app')

@section('title', 'تقرير الدورات المتاحة')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <!-- العنوان -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تقرير الدورات المتاحة</h3>
                </div>
            </div>
        </div>

        <!-- فلاتر البحث -->
        <form method="GET" action="{{ route('admin.reports.courses_report') }}" class="mb-4">
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
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn" style="background-color: #196098;color: white">بحث</button>
                </div>
            </div>
        </form>

        <!-- جدول التقرير -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">قائمة الدورات المتاحة</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>اسم الدورة</th>
                            <th>القسم</th>
                            <th>عدد الدورات المتاحة</th>
                            <th>التاريخ</th>
                            <th>الوقت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>{{ $course->course_name }}</td>
                                <td>{{ $course->department->department_name }}</td>
                                <td>{{ $course->sessions->count() }}</td>
                                <td>{{ $course->created_at->format('Y-m-d') }}</td>
                                <td>{{ $course->sessions->first()->start_time ?? 'غير محدد' }} - {{ $course->sessions->last()->end_time ?? 'غير محدد' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">لا توجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- زر التصدير -->
        <!-- زر التصدير -->
        <div class="row">
            <div class="col-md-12 text-right">
                {{-- <a href="{{ route('admin.reports.export_excel_courses', ['search' => request('search')]) }}" class="btn" style="background-color: #e94c21;color: white"> --}}
                <a href="" class="btn" style="background-color: #e94c21;color: white">
                    تصدير إلى Excel
                </a>
                {{-- <a href="{{ route('admin.reports.export_pdf_courses', ['search' => request('search')]) }}" class="btn" style="background-color: #e94c21;color: white"> --}}
                <a href="" class="btn" style="background-color: #e94c21;color: white">
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

