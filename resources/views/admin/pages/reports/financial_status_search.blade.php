@extends('admin.layouts.app')

@section('title', 'تقرير الحالة المالية للطالب')

@section('content')
<div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
    <div class="content container-fluid">

        <!-- العنوان -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تقرير الحالة المالية للطالب</h3>
                </div>
            </div>
        </div>

        <!-- مربع البحث -->
        <form method="GET" action="{{ route('admin.reports.financial_status_search') }}" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label>بحث حسب الاسم أو البريد الإلكتروني أو الرقم:</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="أدخل الاسم أو البريد أو الرقم">
                </div>
                <div class="col-md-6 mt-3">
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </div>
        </form>

        <!-- نتائج البحث (عرض الطلاب) -->
        @if($students->count() > 0)
        <h4>نتائج البحث</h4>
        <ul class="list-group mb-4">
            @foreach($students as $student)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.reports.view_student_financial_status', $student->id) }}">
                        {{ $student->student_name_ar }} ({{ $student->student_name_en }})
                    </a>
                    <span class="badge badge-info">{{ $student->email }}</span>
                </li>
            @endforeach
        </ul>
        @else
            <p class="text-center">لا توجد نتائج للبحث.</p>
        @endif

        <!-- زر التصدير -->

    </div>
</div>
@endsection
