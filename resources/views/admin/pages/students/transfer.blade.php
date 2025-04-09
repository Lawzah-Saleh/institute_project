@extends('layouts.app')

@section('content')
<div class="container">
    <h1>تحديث الطالب إلى الدورة التالية</h1>

    <!-- مربع البحث لاختيار الطالب -->
    <form method="GET" action="{{ route('students.transfer') }}">
        <input type="text" id="student_search" name="search" class="form-control" placeholder="ابحث عن الطالب بالاسم" autocomplete="off">
        <div id="student_suggestions"></div>
    </form>

    <!-- عرض بيانات الطالب والدورة الحالية -->
    @isset($student)
        <h3>بيانات الطالب</h3>
        <p><strong>الاسم:</strong> {{ $student->student_name_ar }}</p>
        <p><strong>الدورة الحالية:</strong> {{ $currentCourse->course_name }}</p>

        <!-- فورم تسجيل الطالب في الدورة التالية -->
        @isset($nextCourse)
            <h4>الدورة التالية: {{ $nextCourse->course_name }}</h4>
            <form action="{{ route('students.processTransfer', $student->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="amount_paid">المبلغ المدفوع</label>
                    <input type="number" class="form-control" name="amount_paid" required>
                </div>
                <button type="submit" class="btn btn-primary">انتقال إلى الدورة التالية</button>
            </form>
        @else
            <p>لا توجد دورة تالية للتسجيل.</p>
        @endisset
    @endisset

    <!-- عرض رسالة النجاح أو الخطأ -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
</div>

<script>
    // الجافا سكربت للبحث التلقائي عن الطلاب
    document.getElementById('student_search').addEventListener('input', function() {
        let searchTerm = this.value;
        
        if (searchTerm.length >= 3) {
            fetch('/students/search?query=' + searchTerm)
                .then(response => response.json())
                .then(data => {
                    let suggestions = '';
                    data.forEach(student => {
                        suggestions += `<div class="suggestion-item" onclick="selectStudent(${student.id}, '${student.student_name_ar}')">${student.student_name_ar}</div>`;
                    });
                    document.getElementById('student_suggestions').innerHTML = suggestions;
                });
        } else {
            document.getElementById('student_suggestions').innerHTML = '';
        }
    });

    // عند اختيار طالب من الاقتراحات
    function selectStudent(studentId, studentName) {
        document.getElementById('student_search').value = studentName;
        document.getElementById('student_suggestions').innerHTML = '';

        // إرسال الطالب المختار وعرض بياناته
        window.location.href = `/students/${studentId}/edit`;
    }
</script>
@endsection
