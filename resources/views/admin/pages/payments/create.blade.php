@extends('admin.layouts.app')

@section('title', 'إضافة دفع للطالب')

@section('content')
<div class="page-wrapper" style="margin-right: 100px;padding:50px 20px;width: calc(105% - 150px);background:#f7f7fa;">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">إضافة دفع للطالب</h3>
                </div>
            </div>
        </div>

        <!-- بحث الطالب -->
        <form method="GET" action="{{ route('admin.payments.search') }}" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label for="search_student">بحث عن الطالب</label>
                    <input type="text" name="search_student" id="search_student" class="form-control" placeholder="ابحث باسم الطالب أو الرقم" value="{{ old('search_student') }}" required>
                    <ul id="search-results" class="list-group mt-2" style="display: none;"></ul>  <!-- لعرض النتائج -->
                </div>
                <div class="col-md-6 mt-3">
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </div>
        </form>

        <!-- عرض تفاصيل الطالب بعد البحث -->
        @if(isset($student))
        <div class="card mb-4">
            <div class="card-body">
                <h5>معلومات الطالب</h5>
                <p><strong>الاسم:</strong> {{ $student->student_name_ar }} ({{ $student->student_name_en }})</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $student->email }}</p>
                <p><strong>رقم الهاتف:</strong>
                    @php
                        $phones = json_decode($student->phones, true);
                    @endphp
                    {{ $phones ? implode(', ', $phones) : 'غير متوفر' }}
                </p>
            </div>
        </div>

        <!-- عرض الفواتير الخاصة بالطالب -->
        <form action="{{ route('admin.payments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">

            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">الفواتير الخاصة بالطالب</h5>
                    <div class="form-group">
                        <label for="invoice_id">اختر الفاتورة</label>
                        <select name="invoice_id" id="invoice_id" class="form-control" required>
                            <option value="">اختر الفاتورة</option>
                            @foreach($student->invoices as $invoice)
                                <option value="{{ $invoice->id }}">{{ $invoice->invoice_number }} - {{ $invoice->due_date }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-3">
                        <label for="amount_paid">المبلغ المدفوع</label>
                        <input type="number" name="amount_paid" class="form-control" placeholder="أدخل المبلغ المدفوع" required>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">إضافة الدفع</button>
                    </div>
                </div>
            </div>
        </form>
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#search_student').on('keyup', function() {
            let query = $(this).val();

            // عندما يكتب المستخدم أكثر من حرفين
            if(query.length >= 3) {
                $.ajax({
                    url: "{{ route('admin.payments.search') }}",
                    method: 'GET',
                    data: { search_student: query }, // إرسال النص المدخل للبحث
                    success: function(data) {
                        $('#search-results').empty().show();  // إظهار القائمة المنبثقة
                        if(data.length > 0) {
                            data.forEach(function(student) {
                                $('#search-results').append(
                                    `<li class="list-group-item" data-id="${student.id}" style="cursor: pointer;">
                                        ${student.student_name_ar} (${student.student_name_en}) - ${student.id}
                                    </li>`
                                );
                            });
                        } else {
                            $('#search-results').html('<li class="list-group-item">لا توجد نتائج</li>');
                        }
                    }
                });
            } else {
                $('#search-results').hide();  // إخفاء النتائج إذا كان النص أقل من 3 أحرف
            }
        });

        // عند النقر على نتيجة البحث
        $(document).on('click', '#search-results li', function() {
            let studentId = $(this).data('id');
            window.location.href = `/admin/payments/details/${studentId}`;  // الانتقال إلى صفحة تفاصيل الدفع
        });
    });
</script>
@endsection
