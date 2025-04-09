@extends('admin.layouts.app')

@section('title', 'تقرير الدورات المقامة في التاريخ المحدد')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <!-- العنوان -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تقرير الدورات المقامة في التاريخ المحدد</h3>
                </div>
            </div>
        </div>

        <!-- فلاتر البحث -->
        <form method="GET" action="{{ route('admin.reports.courses_on_date') }}" class="mb-4">
            <div class="row ">
                <!-- تاريخ -->
                <div class="col-md-4">
                    <label>اختر التاريخ:</label>
                    <input type="date" name="selected_date" class="form-control" value="{{ request('selected_date') }}">
                </div>
                <div class="col-md-4 text-left ">
                    <button type="submit" class="btn" style="background-color: #196098;color: white">بحث</button>
                </div>
            </div>
        </form>

        <!-- جدول التقرير -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">الدورات المقامة في التاريخ: {{ $selectedDate ?? 'غير محدد' }}</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>اسم الدورة</th>
                            <th>اسم المدرس</th>
                            <th>تاريخ البداية </th>
                            <th>تاريخ النهاية</th>
                            <th>الوقت</th>
                            <th>عدد الجلسات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            @foreach($course->sessions as $session)
                                <tr>
                                    <td>{{ $course->course_name }}</td>
                                    <td>{{ $session->employee->name_ar ?? 'غير محدد' }}</td>
                                    <td>{{ $session->start_date }}</td>
                                    <td>{{ $session->end }}</td>
                                    <td>{{ $session->start_time ?? 'غير محدد' }} - {{ $session->end_time ?? 'غير محدد' }}</td>
                                    <td>{{ $course->sessions->count() }}</td>
                                </tr>
                            @endforeach
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
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="" class="btn" style="background-color: #e94c21;color: white">
                {{-- <a href="{{ route('admin.reports.export_excel_courses_on_date', ['selected_date' => request('selected_date')]) }}" class="btn" style="background-color: #e94c21;color: white"> --}}
                    تصدير إلى Excel
                </a>
                {{-- <a href="{{ route('admin.reports.export_pdf_courses_on_date', ['selected_date' => request('selected_date')]) }}" class="btn" style="background-color: #e94c21;color: white"> --}}
                <a href="" class="btn" style="background-color: #e94c21;color: white">
                    تصدير إلى PDF
                </a>
            </div>
        </div>

    </div>
</div>

<script>
    // يمكن إضافة كود JavaScript أو jQuery هنا إذا كنت بحاجة لتصفية إضافية أو تأثيرات تفاعلية
</script>

@endsection
