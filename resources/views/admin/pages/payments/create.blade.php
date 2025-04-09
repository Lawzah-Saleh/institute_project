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
        <form method="GET" action="#" class="mb-4" id="searchForm">
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
        <div id="student-details"></div> <!-- سيتم هنا عرض تفاصيل الطالب والفواتير -->

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

            // إرسال طلب لتحميل بيانات الطالب
            $.ajax({
                url: `/admin/payments/details/${studentId}`,
                method: 'GET',
                success: function(data) {
                    $('#student-details').html(data);  // عرض بيانات الطالب داخل العنصر المحدد
                }
            });

            $('#search-results').hide();  // إخفاء نتائج البحث بعد اختيار الطالب
        });
    });
</script>
@endsection
