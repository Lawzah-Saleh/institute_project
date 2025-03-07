@extends('layouts.app')

@section('title', ' الكورسات')

@section('content')

<section class="services section light-background mt-5" >
    <div class="container section-title" data-aos="fade-up">
        <h2 style="color: #196098;">الدورات المتاحة</h2>
        <p>الأقسام وما تحتويه من دورات</p>
    </div>
</section>

<section id="services" class="services section light-background mt-4">
    <div class="container">
        @if ($departments->isEmpty())
            <p class="text-center">لا توجد أقسام حالياً.</p>
        @else
            @foreach ($departments as $department)
                <div class="mb-5 text-center">
                    <h2 class="department-title" data-aos="fade-up" style="font-size: 2rem;color: #196098; font-weight:bold; margin-bottom: 20px;"> قسم {{ $department->department_name }}  </h2>

                    <div class="row g-5">
                        @foreach ($department->courses as $course)
                            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                                <div class="service-item item-cyan ">
                                    <i class="bi bi-book icon"></i>
                                    <div>
                                        <h3>{{ $course->course_name }}</h3>
                                        <p>المدة: {{ $course->duration }} ساعة</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</section>
@endsection
