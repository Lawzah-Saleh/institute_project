@extends('admin.layouts.app')

@section('title', 'تفاصيل الطالب')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- 🔹 Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <h3 class="page-title">تفاصيل الطالب</h3>
            <a href="{{ route('students.index', $student->id) }}" ><h3 class="page-title">قائمة الطلاب </h3>

            </a>
        </div>

        <!-- 🔹 Student Profile -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">

                    <!-- Student Image -->
                    <div class="col-md-3 text-center">
                        <img src="{{ $student->image ? asset('storage/' . $student->image) : asset('images/default-student.png') }}" 
                             alt="صورة الطالب" 
                             class="rounded-circle shadow-sm img-thumbnail" 
                             style="width: 140px; height: 140px;">
                        <h5 class="mt-3">{{ $student->student_name_ar }}</h5>
                        <h6 class="text-muted">{{ $student->student_name_en }}</h6>
                        <span class="badge rounded-pill {{ $student->state ? 'bg-success' : 'bg-danger' }}">
                            {{ $student->state ? 'نشط' : 'غير نشط' }}
                        </span>
                    </div>

                    <!-- Student Details -->
                    <div class="col-md-9">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-phone"></i> <strong>الهاتف:</strong></td>
                                    <td>{{ json_decode($student->phones, true) ? implode(', ', json_decode($student->phones)) : 'غير متوفر' }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-envelope"></i> <strong>البريد الإلكتروني:</strong></td>
                                    <td>{{ $student->email ?? 'غير متوفر' }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-user-graduate"></i> <strong>المؤهل:</strong></td>
                                    <td>{{ $student->qualification }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-map-marker-alt"></i> <strong>مكان الميلاد:</strong></td>
                                    <td>{{ $student->birth_place }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-calendar-alt"></i> <strong>تاريخ الميلاد:</strong></td>
                                    <td>{{ $student->birth_date }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-home"></i> <strong>العنوان:</strong></td>
                                    <td>{{ $student->address }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <!-- 🔹 Courses & Sessions -->
        <div class="row">
            <!-- Sessions -->
            <div class="col-md-12" >
                <div class="card ">
                    <div class="card-header  text-dark"style="background-color: #e94c21;">
                        <i class="fas fa-clock"></i> الدورات الملتحق بها
                    </div>
                    <div class="card-body">
                        @if ($student->sessions->isNotEmpty())
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>اسم الكورس</th>
                                        <th>تاريخ البداية</th>
                                        <th>تاريخ النهاية</th>
                                        <th>وقت الجلسة</th>
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
                                            <span class="{{ $statusColors[$status] ?? 'badge bg-dark' }}">{{ ucfirst($status) }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted text-center">🔸 لا يوجد دورات ملتحق بها.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 🔹 Back & Edit Buttons -->
        <div class="mt-4 text-center">
            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> الرجوع
            </a>
            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-success">
                <i class="fas fa-edit"></i> تعديل
            </a>
        </div>

    </div>
</div>
@endsection
