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

    <form action="{{ route('students.register') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 📷 صورة الطالب -->


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
                        <input type="date" name="Day_birth" class="form-control" value="{{ old('Day_birth') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label> مكان الميلاد *</label>
                        <input type="text" name="place_birth" class="form-control" value="{{ old('place_birth') }}" required>
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
                            <input type="text" name="qulification" class="form-control" value="{{ old('qulification') }}" required>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <input type="file" name="photo" id="image" class="form-control">
                        <p class="text-muted mt-2">قم بالسحب والإفلات أو انقر هنا لاختيار صورة</p>
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
                        <input type="number" name="amount_paid" class="form-control" value="{{ old('amount_paid') }}" readonly>
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

        fetch(`/get-courses/${departmentId}`)
            .then(response => response.json())
            .then(data => {
                coursesSelect.innerHTML = '<option value="">اختر دورة</option>';
                if (data.length === 0) {
                    coursesSelect.innerHTML = '<option value="">لا توجد دورات متاحة</option>';
                } else {
                    data.forEach(course => {
                        let option = document.createElement('option');
                        option.value = course.id;
                        option.textContent = course.course_name; // استخدام الاسم الصحيح
                        coursesSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error("خطأ في جلب الدورات:", error);
                coursesSelect.innerHTML = '<option value="">خطأ في تحميل الدورات</option>';
            });
    });
</script>

<script>
    document.getElementById('courses').addEventListener('change', function() {
        let courseId = this.value;
        let amountInput = document.querySelector('input[name="amount_paid"]');

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
</script>


</body>
</html>

{{--
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">Student Registration</h2>

    <form action="{{ route('students.register') }}" method="POST" enctype="multipart/form-data" class="p-4 shadow rounded bg-white">
        @csrf

        <!-- Student Photo -->
        <div class="mb-3 text-center">
            <label for="photo" class="form-label fw-bold">Photo *</label>
            <div class="border p-3 rounded">
                <input type="file" name="photo" id="image" class="form-control">
                <p class="text-muted mt-2">Drag and drop or click here to select a file</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="first_name" class="form-label fw-bold">First Name *</label>
                <input type="text" name="student_name_ar" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="last_name" class="form-label fw-bold">Last Name *</label>
                <input type="text" name="student_name_en" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label fw-bold">Address *</label>
            <input type="text" name="address" class="form-control" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label fw-bold">Email *</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label fw-bold">Phone *</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="birth_date" class="form-label fw-bold">Date of Birth *</label>
                <input type="date" name="Day_birth" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="birth_place" class="form-label fw-bold">Place of Birth *</label>
                <input type="text" name="place_birth" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="qualification" class="form-label fw-bold">Qualification *</label>
                <input type="text" name="qulification" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Gender *</label>
                <div class="d-flex">
                    <div class="form-check me-3">
                        <input type="radio" name="gender" value="Male" class="form-check-input" required>
                        <label class="form-check-label">Male</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="gender" value="Female" class="form-check-input" required>
                        <label class="form-check-label">Female</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="department" class="form-label fw-bold">Department *</label>
                <select name="department" id="department" class="form-select" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                    @endforeach
                </select>

                <!-- Debugging -->
                @if(empty($departments))
                    <p class="text-danger">No departments found!</p>
                @endif

            </div>

            <div class="col-md-6 mb-3">
                <label for="courses" class="form-label fw-bold">Courses *</label>
                <select name="course_id" id="courses" class="form-select" required>
                    <option value="">Select a Course</option>
                </select>
            </div>
        </div>
        <!-- عرض السعر بعد اختيار الكورس -->
        <div class="mt-2">
            <label for="amount_paid" class="form-label fw-bold">Course Price *</label>
            <input type="number" name="amount_paid" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label for="study_time" class="form-label fw-bold">Study Time *</label>
            <select name="time" class="form-select" required>
                <option value="8-10">8-10</option>
                <option value="10-12">10-12</option>
                <option value="2-4">2-4</option>
                <option value="4-6">4-6</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="amount_paid" class="form-label fw-bold">Amount to Pay *</label>
            <input type="number" name="amount_paid" class="form-control" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success px-5">Submit</button>
            <button type="reset" class="btn btn-secondary px-5">Cancel</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('department').addEventListener('change', function() {
        let departmentId = this.value;
        let coursesSelect = document.getElementById('courses');
        coursesSelect.innerHTML = '<option value="">Loading...</option>';

        fetch(`/get-courses/${departmentId}`)
            .then(response => response.json())
            .then(data => {
                coursesSelect.innerHTML = '<option value="">Select a Course</option>';
                if (data.length === 0) {
                    coursesSelect.innerHTML = '<option value="">No courses available</option>';
                } else {
                    data.forEach(course => {
                        let option = document.createElement('option');
                        option.value = course.id;
                        option.textContent = course.course_name; // استخدام الاسم الصحيح
                        coursesSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error("Error fetching courses:", error);
                coursesSelect.innerHTML = '<option value="">Error loading courses</option>';
            });
    });
</script>

<script>
    document.getElementById('courses').addEventListener('change', function() {
        let courseId = this.value;
        let amountInput = document.querySelector('input[name="amount_paid"]');

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
</script>





</body>
</html> --}}
