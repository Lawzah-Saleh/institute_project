@extends('layouts.admin')

@section('title', 'بحث عن الطالب')

@section('content')
    <div class="container">
        <h3>بحث عن طالب</h3>
        
        <!-- استمارة البحث -->
        <div class="form-group">
            <label for="student_name">اسم الطالب</label>
            <input type="text" id="student_name" class="form-control" placeholder="ابحث عن اسم الطالب">
        </div>
        
        <!-- عرض نتائج البحث -->
        <div id="searchResults"></div>
        
        <!-- عرض تفاصيل الطالب عند اختياره -->
        <div id="studentDetails" style="display:none;">
            <h4>تفاصيل الطالب</h4>
            <div id="studentInfo"></div>
        </div>
    </div>

    <!-- إضافة السكربت للتفاعل مع AJAX -->
    <script>
        // عند الكتابة في مربع البحث
        document.getElementById('student_name').addEventListener('input', function() {
            const studentName = this.value;

            // التحقق من أن الاسم ليس فارغاً
            if (studentName.length > 2) {
                // إجراء طلب AJAX للبحث عن الطلاب
                fetch(`/students/search?student_name=${studentName}`)
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        data.students.forEach(student => {
                            html += `
                                <div>
                                    <a href="javascript:void(0)" onclick="showStudentDetails(${student.id})">${student.student_name_ar}</a>
                                </div>
                            `;
                        });
                        document.getElementById('searchResults').innerHTML = html;
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                document.getElementById('searchResults').innerHTML = '';
            }
        });

        // إظهار تفاصيل الطالب عند الضغط على اسمه
        function showStudentDetails(studentId) {
            fetch(`/students/${studentId}/details`)
                .then(response => response.json())
                .then(data => {
                    // عرض تفاصيل الطالب
                    let detailsHtml = `
                        <strong>اسم الطالب:</strong> ${data.student.student_name_ar} <br>
                        <strong>الجلسة الحالية:</strong> ${data.currentSession ? data.currentSession.course_session.session_name : 'لا توجد جلسة حالياً'} <br>
                    `;

                    // إذا كانت الجلسة مكتملة، عرض زر "إضافة للدورة التالية"
                    if (data.currentSession && data.currentSession.status === 'complete') {
                        detailsHtml += `
                            <form action="/students/${studentId}/register-next-course" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">إضافة للدورة التالية</button>
                            </form>
                        `;
                    }

                    // عرض التفاصيل في الصفحة
                    document.getElementById('studentInfo').innerHTML = detailsHtml;
                    document.getElementById('studentDetails').style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
