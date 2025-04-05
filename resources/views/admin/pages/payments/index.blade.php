@extends('admin.layouts.app')

@section('title', 'إدارة المدفوعات')

@section('content')
<div class="page-wrapper" style="margin-right: 100px;padding:50px 20px;width: calc(105% - 150px);background:#f7f7fa;">
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
                        <option value="" selected>-- اختر القسم --</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>الدورة</label>
                    <select name="course_id" id="course_id" class="form-control" disabled>
                        <option value="">-- اختر الدورة --</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>الجلسة</label>
                    <select name="session_id" id="session_id" class="form-control" disabled>
                        <option value="">-- اختر الجلسة --</option>
                    </select>
                </div>
                <div class="col-md-8 mt-3">
                    <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو البريد أو الهاتف">
                </div>
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary"> بحث</button>
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
                                @forelse($students as $student)
                                <tr>
                                    <td>{{ $student->student_name_ar }} ({{ $student->student_name_en }})</td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        @php $phones = json_decode($student->phones, true); @endphp
                                        {{ $phones ? implode(', ', $phones) : 'غير متوفر' }}
                                    </td>
                                    <td>{{ number_format($student->invoices_sum_amount, 2) }} ريال</td>
                                    <td>{{ number_format($student->payments_sum_amount, 2) }} ريال</td>
                                    <td>{{ number_format($student->invoices_sum_amount - $student->payments_sum_amount, 2) }} ريال</td>

                                    <td>
                                        @php
                                            $total     = $student->invoices_sum_amount ?? 0;
                                            $paid      = $student->payments_sum_amount ?? 0;
                                            $remaining = $total - $paid;
                                        @endphp

                                        @if($remaining <= 0 && $total > 0)
                                            <span class="badge bg-success">مدفوع بالكامل</span>
                                        @elseif($paid > 0 && $paid < $total)
                                            <span class="badge bg-warning text-dark">مدفوع جزئياً</span>
                                        @elseif($paid == 0 && $total > 0)
                                            <span class="badge bg-danger">غير مدفوع</span>
                                        @else
                                            <span class="badge bg-secondary">لا توجد بيانات</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.student.payment.details', $student->id) }}" class="btn btn-sm btn-info">
                                            عرض التفاصيل
                                        </a>
                                    </td>





                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد نتائج لعرضها.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('filterForm').reset();
});

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
