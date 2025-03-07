@extends('Teacher-dashboard.layouts.app')

@section('title', 'Student')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid" style="background-color:f9f9fb">

        <!-- Page Header -->
        <div class="page-header" >
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">الطلاب</h3>
                    </div>
                </div>
            </div>
        </div>


        <div class="student-group-form mb-4">
            <div class="row">

                <div class="col-lg-6 col-md-12 mb-3">


                    <!-- Dropdown للكورسات -->
                    <div class="form-group">
                        <label>الكورسات</label>
                        <select id="course" class="form-control">
                            <option value="">اختر الكورس</option>
                        </select>
                    </div>
                </div>

                <!-- البحث برقم الطالب أو الاسم -->
                <div class="col-lg-6 col-md-12 mb-3">

                    <div class="form-group">
                        <input type="text" id="search-name" class="form-control" placeholder="البحث بالاسم...">
                    </div>
                    <div class="search-student-btn mt-2">
                        <button type="button" class="btn btn-primary w-100" id="search-button" style="background: #e94c21">Search</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول عرض الطلاب -->

                        <div class="table-responsive">
                            <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>رقم الطالب</th>
                                        <th>الأسم</th>
                                        <th>الدرجات</th>
                                        <th>النسبة</th>



                                    </tr>
                                </thead>
                                <tbody id="students-table-body">

                                    <!-- البيانات ستظهر هنا عبر AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- JavaScript لجلب البيانات عبر AJAX -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script>
    // $(document).ready(function() {
        // عند اختيار القسم، يتم جلب الكورسات
        // $('#section').change(function() {
        //     var section_id = $(this).val();
        //     $('#course').empty().append('<option value="">اختر الكورس</option>');
        //     $('#students-table-body').empty();

        //     if (section_id) {
        //         $.ajax({
        //             url: '/get-courses/' + section_id,
        //             type: 'GET',
        //             success: function(courses) {
        //                 $.each(courses, function(index, course) {
        //                     $('#course').append('<option value="' + course.id + '">' + course.course_name + '</option>');
        //                 });
        //             }
        //         });
        //     }
        // });

        // عند اختيار الكورس، يتم جلب الطلاب
        // $('#course').change(function() {
        //     var course_id = $(this).val();
        //     $('#students-table-body').empty();

        //     if (course_id) {
        //         $.ajax({
        //             url: '/get-students/' + course_id,
        //             type: 'GET',
        //             success: function(students) {
        //                 $.each(students, function(index, student) {
        //                     $('#students-table-body').append(
        //                         '<tr>' +
        //                             '<td>' + student.id + '</td>' +
        //                             '<td>' +
        //                                 '<div class="d-flex align-items-center">' +
        //                                     '<img src="' + (student.image ? '/storage/student_images' + student.image : 'default_profile.png') + '" ' +
        //                                         'class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="Student Image">' +
        //                                     '<a href="/students/' + student.id + '/profile" class="text-primary fw-bold">' + student.student_name_ar + '</a>' +
        //                                 '</div>' +
        //                             '</td>' +
        //                             '<td>' + student.section_name + '</td>' +
        //                             '<td>' + student.course_name + '</td>' +
        //                             '<td>' + student.gender + '</td>' +
        //                             '<td>' + student.phone + '</td>' +
        //                             '<td>' + student.address + '</td>' +
        //                             '<td>' + student.email + '</td>' +
        //                             '<td>' + student.Day_birth + '</td>' +
        //                             '<td>' + student.place_birth + '</td>' +
        //                             '<td>' + student.qulification + '</td>' +
        //                             '<td>' + student.state + '</td>' +
        //                             '<td class="text-end">' +
        //                                 '<a href="/students/' + student.id + '/profile" class="btn btn-sm bg-info-light">' +
        //                                     '<i class="feather-user"></i>' +
        //                                 '</a>' +
        //                                 '<a href="/students/' + student.id + '/edit" class="btn btn-sm bg-danger-light">' +
        //                                     '<i class="feather-edit"></i>' +
        //                                 '</a>' +
        //                                                                         // زر الحذف مع رسالة تأكيد
        //                                 '<button class="btn btn-sm bg-danger-light delete-student" data-id="' + student.id + '">' +
        //                                     '<i class="fas fa-trash"></i>' +
        //                                 '</button>' +

        //                             '</td>' +
        //                         '</tr>'
        //                     );



        //                 });
        //             }
        //         });
        //     }
        // });

// // البحث برقم الطالب أو الاسم
// $('#search-button').click(function() {
    // var searchId = $('#search-id').val();
    // var searchName = $('#search-name').val();

    // $('#students-table-body').empty();

    // $.ajax({
    //     url: '/search-students',
    //     type: 'GET',
    //     data: { id: searchId, name: searchName },
    //     success: function(students) {
    //         $.each(students, function(index, student) {
    //             $('#students-table-body').append(
                    // '<tr>' +
                    //     '<td>' + student.id + '</td>' +
                    //     '<td>' + student.student_name_ar + '</td>' +
                    //     '<td>' + (student.section ? student.section.name : 'غير متوفر') + '</td>' +
                    //     '<td>' + (student.course ? student.course.course_name : 'غير متوفر') + '</td>' +
                    //     '<td>' + student.gender + '</td>' +
                    //     '<td>' + student.phone + '</td>' +
                    //     '<td>' + student.address + '</td>' +
                    //     '<td>' + student.email + '</td>' +
                    //     '<td>' + student.Day_birth + '</td>' +
                    //     '<td>' + student.place_birth + '</td>' +
                    //     '<td>' + student.qulification + '</td>' +
                    //     '<td>' + student.state + '</td>' +
                    //     '<td class="text-end">' +
                    //                     '<a href="/students/' + student.id + '/profile" class="btn btn-sm bg-info-light">' +
                    //                         '<i class="feather-user"></i>' +
                    //                     '</a>' +
                    //                     '<a href="/students/' + student.id + '/edit" class="btn btn-sm bg-danger-light">' +
                    //                         '<i class="feather-edit"></i>' +
                    //                     '</a>' +
                    //                                                             // زر الحذف مع رسالة تأكيد
                    //                     '<button class="btn btn-sm bg-danger-light delete-student" data-id="' + student.id + '">' +
                    //                         '<i class="fas fa-trash"></i>' +
                    //                     '</button>' +

                    //                 '</td>' +
                    // '</tr>'
//                 );
//             });
//         },
//         error: function() {
//             alert('حدث خطأ أثناء البحث. يرجى المحاولة مرة أخرى.');
//         }
//     });
// });

//     });

//     $(document).on('click', '.delete-student', function () {
//     var studentId = $(this).data('id');
//     if (confirm('هل أنت متأكد أنك تريد حذف هذا الطالب؟')) {
//         $.ajax({
//             url: '/students/' + studentId,
//             type: 'DELETE',
//             headers: {
//                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
//             },
//             success: function (response) {
//                 alert('تم حذف الطالب بنجاح');
//                 location.reload(); // إعادة تحميل الصفحة لتحديث الجدول
//             },
//             error: function () {
//                 alert('حدث خطأ أثناء الحذف. يرجى المحاولة مرة أخرى.');
//             }
//         });
//     }
// });

// </script>
// <!-- Delete Confirmation Modal -->
// <div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-labelledby="deleteStudentLabel" aria-hidden="true">
{{-- //     <div class="modal-dialog modal-dialog-centered">
//         <div class="modal-content">
//             <div class="modal-header">
//                 <h5 class="modal-title" id="deleteStudentLabel">تأكيد الحذف</h5>
//                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
//             </div>
//             <div class="modal-body">
//                 هل أنت متأكد أنك تريد حذف هذا الطالب؟
//             </div>
//             <div class="modal-footer">
//                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
//                 <button type="button" class="btn btn-danger" id="confirmDeleteBtn">حذف</button>
//             </div>
//         </div>
//     </div>
// </div> --}}

@endsection
