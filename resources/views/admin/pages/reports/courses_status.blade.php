@extends('admin.layouts.app')

@section('title', 'تقرير الدورات المنتهية وغير المنتهية')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <!-- العنوان -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تقرير الدورات المنتهية وغير المنتهية</h3>
                </div>
            </div>
        </div>

        <!-- فلاتر البحث -->
        <form method="GET" action="{{ route('admin.reports.courses_status') }}" class="mb-4">
            <div class="row">
                <!-- فلتر الحالة -->
                <div class="col-md-4">
                    <label>اختر الحالة:</label>
                    <select name="status" class="form-control">
                        <option value="">-- اختر الحالة --</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>منتهية</option>
                        <option value="incompleted" {{ request('status') == 'incompleted' ? 'selected' : '' }}>غير منتهية</option>
                    </select>
                </div>
                <div class="col-md-4 text-left">
                    <button type="submit" class="btn" style="background-color: #196098;color: white">بحث</button>
                </div>
            </div>
        </form>

        <!-- جدول التقرير -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">الدورات حسب الحالة: {{ request('status') == 'completed' ? 'منتهية' : (request('status') == 'incompleted' ? 'غير منتهية' : 'جميع الدورات') }}</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>اسم الدورة</th>
                            <th>عدد الجلسات</th>
                            <th>تاريخ البداية</th>
                            <th>تاريخ الانتهاء</th>
                            <th>حالة الدورة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <td>{{ $course->course_name }}</td>
                            <td>{{ $course->sessions->count() }}</td>
                            <td>{{ $course->sessions->first()->start_date ?? 'غير محدد' }}</td>
                            <td>
                                @if($course->sessions->last() && $course->sessions->last()->end_date)
                                    {{ $course->sessions->last()->end_date }}
                                @else
                                    {{ 'غير محدد' }}
                                @endif
                            </td>
                            <td>
                                @if($course->sessions->last() && $course->sessions->last()->end_date < now())
                                    منتهية
                                @else
                                    غير منتهية
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    
                    </tbody>
                </table>
            </div>
        </div>

        <!-- زر التصدير -->
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="" class="btn" style="background-color: #e94c21;color: white">
                {{-- <a href="{{ route('admin.reports.export_excel_courses_status', ['status' => request('status')]) }}" class="btn" style="background-color: #e94c21;color: white"> --}}
                    تصدير إلى Excel
                </a>
                <a href="" class="btn" style="background-color: #e94c21;color: white">
                {{-- <a href="{{ route('admin.reports.export_pdf_courses_status', ['status' => request('status')]) }}" class="btn" style="background-color: #e94c21;color: white"> --}}
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
