<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الطلاب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100%;
            background: #f8f9fa;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .container {
            width: 100%;
            height: 100%;
            max-width: fit-content;

        }
        .card {
            background: rgba(255, 255, 255, 0.9); /* خلفية شفافة للبطاقة */

            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background: rgba(233, 76, 33, 0.9);
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        label {
            font-weight: bold;
            color: #196098;
        }
        .form-control, .form-select {
            border-radius: 5px;
            border: 1px solid #196098;
        }
        .btn-primary {
            background: rgba(25, 96, 152, 0.9);
            border: none;
            padding: 12px 20px;
            font-size: 18px;
            transition: 0.3s;
            width: 100%;
        }
        .btn-primary:hover {
            background: #164b7d;
        }
        .btn-secondary {
            background: #e94c21;
            border: none;
            padding: 12px 20px;
            font-size: 18px;
            transition: 0.3s;
            width: 100%;
        }
        .btn-secondary:hover {
            background: #c03d1a;
        }
        .form-check-label {
            color: #196098;
        }
        .form-check-input:checked {
            background-color: #e94c21;
            border-color: #e94c21;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card" style="margin-top: 20px; margin-left: auto; margin-right: auto;">
            <div class="card-header"style="background: rgba(25, 96, 152, 0.8);">صفحة تسجيل طالب </div>
        </div>

        <form action="{{ route('students.register.submit') }}" method="POST" >
            @csrf

        <!-- 📷 صورة الطالب -->
        @if ($errors->any())
        <div class="alert alert-danger">
            <strong>⚠️ حدثت أخطاء أثناء الإرسال:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

        <!-- 👤 المعلومات الشخصية -->
        <div class="card">
            <div class="card-header" >المعلومات الشخصية</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>الاسم بالعربي *</label>
                        <input type="text" name="student_name_ar" class="form-control" value="{{ old('student_name_ar') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>الاسم بالانجليزي *</label>
                        <input type="text" name="student_name_en" class="form-control" value="{{ old('student_name_en') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label> العنوان *</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>📧 البريد الإلكتروني *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>📞 رقم الهاتف *</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>📅 تاريخ الميلاد *</label>
                        <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label> مكان الميلاد *</label>
                        <input type="text" name="birth_place" class="form-control" value="{{ old('birth_place') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>🚻 الجنس *</label>
                        <div class="d-flex">
                            <div class="form-check me-3">
                                <input type="radio" name="gender" value="Male" class="form-check-input" {{ old('gender') == 'Male' ? 'checked' : '' }} required>
                                <label class="form-check-label">ذكر</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="gender" value="Female" class="form-check-input" {{ old('gender') == 'Female' ? 'checked' : '' }} required>
                                <label class="form-check-label">أنثى</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>🎓 المؤهل العلمي *</label>
                            <input type="text" name="qualification" class="form-control" value="{{ old('qualification') }}" required>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <input type="file" name="photo" id="image" class="form-control">
                        <p class="text-muted mt-2">قم بالسحب والإفلات أو انقر هنا لاختيار صورة</p>
                        <img id="preview" class="img-thumbnail mt-2" style="max-height: 200px; display:none;">

                    </div>
                </div>
            </div>
        </div>


        <!-- 🏫 معلومات الدورة -->
        <div class="card">
            <div class="card-header">معلومات المعهد</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>🏫 القسم *</label>
                        <select name="department" id="department" class="form-select" required>
                            <option value="">اختر القسم</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department') == $department->id ? 'selected' : '' }}>{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>📚 الدورات *</label>
                        <select name="course_id" id="courses" class="form-select" required>
                            <option value="">اختر دورة</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>💰 سعر الدورة *</label>
                        <input type="number" name="course_price" class="form-control" value="{{ old('course_price') }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="study_time">وقت الدراسة *</label>
                        <select name="time" class="form-select" required>
                            <option value="8-10" {{ old('time') == '8-10' ? 'selected' : '' }}>8-10</option>
                            <option value="10-12" {{ old('time') == '10-12' ? 'selected' : '' }}>10-12</option>
                            <option value="2-4" {{ old('time') == '2-4' ? 'selected' : '' }}>2-4</option>
                            <option value="4-6" {{ old('time') == '4-6' ? 'selected' : '' }}>4-6</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount_paid" class="form-label fw-bold">المبلغ المدفوع *</label>
                        <input type="number" name="amount_paid" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">✅ تسجيل</button>
            <button type="reset" class="btn btn-secondary">❌ إلغاء</button>

        </div>
    </form>
</div>


<script>
    document.getElementById('department').addEventListener('change', function() {
        let departmentId = this.value;
        let coursesSelect = document.getElementById('courses');
        coursesSelect.innerHTML = '<option value="">جاري التحميل ...</option>';

        fetch(`/department/${departmentId}/first-course`)
    .then(response => response.json())
    .then(data => {
        coursesSelect.innerHTML = '<option value="">اختر دورة</option>';
        if (data.length === 0) {
            coursesSelect.innerHTML = '<option value="">لا توجد دورات متاحة</option>';
        } else {
            data.forEach(course => {
                let option = document.createElement('option');
                option.value = course.id;
                option.textContent = course.course_name;
                coursesSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error("خطأ في جلب الدورات:", error);
        coursesSelect.innerHTML = '<option value="">خطأ في تحميل الدورات</option>';
    });
    });

    document.getElementById('courses').addEventListener('change', function() {
        let courseId = this.value;
        let amountInput = document.querySelector('input[name="course_price"]');

        if (!courseId) {
            amountInput.value = ''; // إذا لم يتم اختيار كورس، يترك الحقل فارغًا
            return;
        }

        fetch(`/get-course-price/${courseId}`)
            .then(response => response.json())
            .then(data => {
                amountInput.value = data.price || 0; // تحديث السعر تلقائيًا
            })
            .catch(error => {
                console.error("Error fetching course price:", error);
                amountInput.value = 'Error';
            });
    });
    document.getElementById('image').addEventListener('change', function (event) {
    let reader = new FileReader();
    reader.onload = function () {
        let preview = document.getElementById('preview');
        preview.src = reader.result;
        preview.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
});

</script>


</body>
</html>

