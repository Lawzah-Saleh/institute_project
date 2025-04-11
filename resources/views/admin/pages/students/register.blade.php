<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الطلاب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {
            height: 100%;
            background: #f0f4f8; /* Lighter background color */
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 0;
            width: 100%;
            font-family: 'Arial', sans-serif;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 30px auto;
        }

        .card {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }

        .card-header {
            background: #196098;
            color: #fff;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            padding: 15px;
            border-radius: 10px;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #196098;
            padding: 12px;
        }

        .btn-primary {
            background-color: #196098;
            color: white;
            font-size: 16px;
            padding: 10px;
            width: 100%;
            border-radius: 10px;
            border: none;
        }

        .btn-primary:hover {
            background-color: #196098;
        }

        .btn-secondary {
            background-color: #e94c21;
            color: white;
            font-size: 16px;
            padding: 10px;
            width: 100%;
            border-radius: 10px;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #e94c21;
        }

        label {
            font-weight: bold;
            color: #196098;
        }

        .form-check-label {
            color: #196098;
        }

        .form-check-input:checked {
            background-color: #e94c21;
            border-color: #e94c21;
        }

        .form-check {
            padding-left: 1.5rem;
        }

        .phones-container {
            margin-top: 10px;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: gray;
            margin-top: 30px;
        }

    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">تسجيل طالب جديد</div>

        <form action="{{ route('students.register.submit') }}" method="POST">
            @csrf

            <!-- عرض الأخطاء -->
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

            <!-- 📷 صورة الطالب -->
            <div class="card">
                <div class="card-header">المعلومات الشخصية</div>
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
                        <label>العنوان *</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>📧 البريد الإلكتروني *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phones">📞 رقم الهاتف</label>
                            <div id="phones-container">
                                <input type="text" name="phone[]" class="form-control mb-2" placeholder="أدخل رقم الهاتف">
                            </div>
                            <button type="button" id="add-phone" class="btn btn-secondary mt-2">إضافة رقم هاتف</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>📅 تاريخ الميلاد *</label>
                            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>مكان الميلاد *</label>
                            <input type="text" name="birth_place" class="form-control" value="{{ old('birth_place') }}" required>
                        </div>
                    </div>

                    <div class="row">
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
                            <input type="number" name="course_price" class="form-control" value="{{ old('course_price') }}"readonly >
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

</div>

<script>
    // Add Phone Functionality
    document.getElementById('add-phone').addEventListener('click', function() {
        const newPhoneInput = document.createElement('input');
        newPhoneInput.type = 'text';
        newPhoneInput.name = 'phone[]';  // Ensure it's sent as an array
        newPhoneInput.classList.add('form-control', 'mb-2');
        newPhoneInput.placeholder = 'أدخل رقم الهاتف';

        document.getElementById('phones-container').appendChild(newPhoneInput);
    });

    // Department and Courses fetch logic
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
    document.getElementById('courses').addEventListener('change', function () {
    let courseId = this.value;
    let priceInput = document.querySelector('input[name="course_price"]');

    if (courseId) {
        fetch(`/courses/${courseId}/price`)
            .then(response => response.json())
            .then(data => {
                priceInput.value = data.price ?? 'غير متوفر';
            })
            .catch(error => {
                console.error('خطأ في تحميل السعر:', error);
                priceInput.value = '';
            });
    } else {
        priceInput.value = '';
    }
});

</script>

</body>
</html>
