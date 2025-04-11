@extends('Teacher-dashboard.layouts.app')
@section('title', 'T-course')
@section('content')

<div style="margin-right: 100px; padding: 50px 20px; width: calc(100% - 50px); background: #f7f7fa;">

    <!-- كرت الترحيب -->
    <div class="flex justify-center mb-4">
        <div class="bg-white shadow-md rounded-lg p-4 text-center w-full md:w-2/3">
            <h3 class="text-2xl fw-bold text-gray-800">الدورات</h3>
        </div>
    </div>

    <!-- رسالة الخطأ -->
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- الكورسات -->
    <div class="row gy-4">
        @foreach($courseSessions as $index => $session)
            <div class="col-xl-3 col-md-6 col-sm-12">
                <div class="course-card shadow-sm p-4 bg-white rounded">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold mb-0">
                            <img src="{{ asset('admin/assets/img/icons/dash-icon-04.svg') }}"
                                 alt="Icon" style="width: 54px; height: 54px; margin-left: 10px;">
                            {{ $session->course->course_name }}
                        </h5>

                        <!-- زر عرض التفاصيل -->
                        {{-- <button class="btn btn-sm btn-outline-primary toggle-details"
                                data-target="details-{{ $index }}"
                                style="border-radius: 20px;">
                            🔍
                        </button> --}}
                        <!-- زر عرض التفاصيل -->
<button class="btn btn-sm btn-outline-primary toggle-details"
data-target="details-{{ $index }}"
style="border-radius: 20px;">
🔍
</button>




                    </div>

                    <!-- التفاصيل المخفية -->
                    <div id="details-{{ $index }}" class="course-details" style="display: none;">
                        <ul class="list-unstyled small mt-3">
                            <li><strong>📅 تاريخ البداية:</strong> {{ $session->start_date }}</li>
                            <li><strong>📅 تاريخ النهاية:</strong> {{ $session->end_date ?? 'مستمرة' }}</li>
                            <li><strong>⏰ وقت البداية:</strong> {{ $session->start_time }}</li>
                            <li><strong>⏰ وقت النهاية:</strong> {{ $session->end_time }}</li>
                            <li><strong>🕓 عدد الساعات اليومية:</strong> {{ $session->daily_hours }}</li>
                        </ul>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection

<!-- CSS -->
<style>
.course-card {
    transition: 0.3s ease;
    border: 1px solid #e3e3e3;
}
.course-card:hover {
    transform: scale(1.03);
    box-shadow: 0px 6px 12px rgba(0,0,0,0.15);
}
</style>

<!-- JavaScript -->





<script>
    document.addEventListener("DOMContentLoaded", function () {
        const buttons = document.querySelectorAll('.toggle-details');
        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const target = document.getElementById(targetId);
                if (target.style.display === "none") {
                    target.style.display = "block";
                } else {
                    target.style.display = "none";
                }
            });
        });
    });
</script>
