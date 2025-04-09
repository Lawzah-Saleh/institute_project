@extends('admin.layouts.app')

@section('title', 'إدارة المدفوعات')

@section('content')
<div class="page-wrapper" style="background-color: #F9F9FB;">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">إدارة المدفوعات</h3>
                </div>
            </div>
        </div>

        <!-- 🔍 Filters -->
        <form method="GET" action="{{ route('admin.payments.index') }}" class="mb-4" id="filterForm">
            <div class="row">
                <div class="col-md-4">
                    <label>القسم</label>
                    <select name="department_id" id="department_id" class="form-control">
                        <option value="">-- اختر القسم --</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>الدورة</label>
                    <select name="course_id" id="course_id" class="form-control" {{ request('department_id') ? '' : 'disabled' }}>
                        <option value="">-- اختر الدورة --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->course_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>الدورة الحالية</label>
                    <select name="session_id" id="session_id" class="form-control" {{ request('course_id') ? '' : 'disabled' }}>
                        <option value="">-- اختر الدورة --</option>
                    </select>
                </div>
                <div class="col-md-8 mt-3">
                    <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو البريد أو الهاتف" value="{{ request('search') }}">
                </div>
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn "style="background-color: #196098;color: white;">بحث</button>
                </div>
            </div>
        </form>

        <!-- 📋 Payment Table -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <h3 class="page-title">قائمة المدفوعات</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>رقم الطالب</th>
                                    <th>اسم الطالب</th>
                                    <th>البريد</th>
                                    <th>رقم الهاتف</th>
                                    <th>المبلغ الكلي</th>
                                    <th>المبلغ المدفوع</th>
                                    <th>المتبقي</th>
                                    <th>الحالة</th>
                                    <th class="text-end">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                @php
                                    // 1. مجموع المبالغ الكلية من جميع دفعات الطالب
                                    $totalAmount = $student->payments->sum('total_amount');
                            
                                    // 2. مجموع المبالغ المدفوعة من جميع الفواتير المرتبطة بالدفعات
                                    $paidAmount = $student->invoices->sum('amount');
                            
                                    // 3. حساب المتبقي
                                    $remaining = $totalAmount - $paidAmount;
                                @endphp
                            
                                <tr>
                                    <td>{{ $student->id }}</td>
                                    <td>{{ $student->student_name_ar }} ({{ $student->student_name_en }})</td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ is_array($phones = json_decode($student->phones, true)) ? implode(',', $phones) : 'غير متوفر' }}</td>
                                    <td>{{ number_format($totalAmount, 2) }} ريال</td>
                                    <td>{{ number_format($paidAmount, 2) }} ريال</td>
                                    <td>{{ number_format($remaining, 2) }} ريال</td>
                                    <td>
                                        @if($remaining <= 0 && $totalAmount > 0)
                                            <span class="badge "style="background-color: #e94c21; color: #fff;">مدفوع بالكامل</span>
                                        @elseif($paidAmount > 0 && $remaining > 0)
                                            <span class="badge  " style="background-color: #e94c21; color: #fff;">مدفوع جزئياً</span>
                                        @elseif($paidAmount == 0 && $totalAmount > 0)
                                            <span class="badge "style="background-color: #e94c21; color: #fff;">غير مدفوع</span>
                                        @else
                                            <span class="badge bg-secondary">لا توجد بيانات</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.student.payment.details', $student->id) }}" class="btn btn-sm"style="background-color: #196098; color: #fff;">عرض التفاصيل</a>
                                    </td>
                                </tr>
                            @endforeach
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('department_id').addEventListener('change', function () {
    const departmentId = this.value;
    const courseSelect = document.getElementById('course_id');
    const sessionSelect = document.getElementById('session_id');

    courseSelect.innerHTML = '<option value="">-- اختر الدورة --</option>';
    sessionSelect.innerHTML = '<option value="">-- اختر الجلسة --</option>';
    courseSelect.disabled = true;
    sessionSelect.disabled = true;

    if (departmentId) {
        fetch(`/get-courses/${departmentId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = course.course_name;
                    courseSelect.appendChild(option);
                });
                courseSelect.disabled = false;
            });
    }
});

document.getElementById('course_id').addEventListener('change', function () {
    const courseId = this.value;
    const sessionSelect = document.getElementById('session_id');

    sessionSelect.innerHTML = '<option value="">-- اختر الجلسة --</option>';
    sessionSelect.disabled = true;

    if (courseId) {
        fetch(`/get-sessions/${courseId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(session => {
                    const option = document.createElement('option');
                    option.value = session.id;
                    option.textContent = `${session.start_date} - ${session.end_date}`;
                    sessionSelect.appendChild(option);
                });
                sessionSelect.disabled = false;
            });
    }
});
</script>
@endsection
