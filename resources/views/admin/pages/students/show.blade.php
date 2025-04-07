@extends('admin.layouts.app')

@section('title', 'تفاصيل الطالب')

@section('content')
<div class="page-wrapper" style="background-color: #F9F9FB;" >
    <div class="content container-fluid">

        <!-- 🔹 Page Header -->


        <!-- ✅ كارد موحد يشمل كل التفاصيل -->
        <div class="card shadow-lg rounded-3">
            <div class="card-header text-white d-flex justify-content-between "  >
                <h4 class="card-title mb-0 align-items-center">بيانات الطالب والدورات</h4>
                <span class="badge rounded-pill {{ $student->state ? 'bg-success' : 'bg-danger' }}">
                    {{ $student->state ? 'نشط' : 'غير نشط' }}
                </span>
            </div>

            <div class="card-body">
                <div class="row align-items-start">
                    <!-- 📷 صورة الطالب -->
                    <div class="col-md-3 text-center">
                        <br><br>
                        <img src="{{ $student->image ? asset('storage/' . $student->image) : asset('default_profile.png') }}"
                             alt="صورة الطالب"
                             class="rounded-circle shadow-lg img-thumbnail mb-3"
                             style="width: 150px; height: 150px;">
                    </div>

                    <!-- 📋 معلومات الطالب -->
                    <div class="col-md-9">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <td width="25%"><strong><i class="fas fa-user"></i> الاسم بالعربية:</strong></td>
                                    <td>{{ $student->student_name_ar }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-user"></i> الاسم بالإنجليزية:</strong></td>
                                    <td>{{ $student->student_name_en }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-phone-alt"></i> الهاتف:</strong></td>
                                    <td>{{ json_decode($student->phones, true) ? implode(', ', json_decode($student->phones)) : 'غير متوفر' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-envelope"></i> البريد الإلكتروني:</strong></td>
                                    <td>{{ $student->email ?? 'غير متوفر' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-graduation-cap"></i> المؤهل:</strong></td>
                                    <td>{{ $student->qualification }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-calendar-alt"></i> تاريخ الميلاد:</strong></td>
                                    <td>{{ $student->birth_date }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-map-marker-alt"></i> مكان الميلاد:</strong></td>
                                    <td>{{ $student->birth_place }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-home"></i> العنوان:</strong></td>
                                    <td>{{ $student->address }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 🟦 الدورات داخل نفس الكارد -->
                <hr class="my-4">
                <h5 class="mb-3 "><i class="fas fa-book"></i> الدورات الملتحق بها:</h5>

                @if ($student->sessions->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>اسم الدورة</th>
                                    <th>تاريخ البداية</th>
                                    <th>تاريخ النهاية</th>
                                    <th>وقت الدورة المتاحة</th>
                                    <th>حالة الطالب</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($student->sessions as $session)
                                <tr>
                                    <td>{{ $session->course->course_name ?? 'غير معروف' }}</td>
                                    <td>{{ $session->start_date }}</td>
                                    <td>{{ $session->end_date }}</td>
                                    <td>{{ $session->start_time }} - {{ $session->end_time }}</td>
                                    <td>
                                        @php
                                            $status = $session->pivot->status ?? 'غير محدد';
                                            $statusColors = [
                                                'pending' => 'badge bg-secondary',
                                                'in_progress' => 'badge bg-info',
                                                'completed' => 'badge bg-success',
                                                'failed' => 'badge bg-danger',
                                                'dropped' => 'badge bg-warning'
                                            ];
                                        @endphp
                                        <span class="{{ $statusColors[$status] ?? 'badge' }}" style="background-color: #e94c21">{{ ucfirst($status) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning text-center">🔸 لا يوجد دورات ملتحق بها.</div>
                @endif
            </div>
        </div>

        <!-- ✅ أزرار التحكم -->
        <div class="mt-4 text-center">
            <a href="{{ route('students.edit', ['id' => $student->id, 'section' => 'personal']) }}" class="btn mx-2" style="background-color: #e94c21;color: white">
                  تعديل البيانات الشخصية
            </a>
            <a href="{{ route('students.edit', ['id' => $student->id, 'section' => 'academic']) }}" class="btn mx-2" style="background-color: #e94c21;color: white">
                  تعديل بيانات الكورس والجلسة
            </a>
            <a href="{{ route('students.edit', ['id' => $student->id, 'section' => 'financial']) }}" class="btn  mx-2" style="background-color: #e94c21;color: white">
                  تعديل البيانات المالية
            </a>

        </div>
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> العودة إلى قائمة الطلاب
            </a>
        </div>

    </div>
</div>
@endsection
