@extends('Teacher-dashboard.layouts.app')
@section('title', 'لوحة تحكم الأستاذ')
@section('content')

<div style="margin-right: 100px; padding: 50px 20px; width: calc(100% - 50px); height:100%; background: #f7f7fa;">
    <!-- Welcome Card -->
    <div class="flex justify-center mb-6">
        <div class="bg-white shadow-md rounded-lg p-6 text-center w-full md:w-2/3">
            <h3 class="text-2xl font-bold text-gray-700">مرحبًا أستاذ {{ Auth::user()->name }}</h3>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row" style="display: flex; gap: 20px;">
        <!-- Students Card -->
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="mycard" style="background: white; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); border-radius: 12px; padding: 20px; width: 100%;">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 style="font-size: 1.3rem; color: #555;">الطلاب</h6>
                            <h3 style="font-size: 2rem; font-weight: bold; color: #e67e22;">+{{ $students_count ?? 0 }}</h3>
                        </div>
                        <div>
                            <img src="{{ asset('Teacher/assets/img/icons/dash-icon-01.png') }}" alt="Dashboard Icon" style="height: 70px; width: 70px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Courses Card -->
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="mycard" style="background: white; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); border-radius: 12px; padding: 20px; width: 100%;">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6 style="font-size: 1.3rem; color: #555;">الكورسات</h6>
                            <h3 style="font-size: 2rem; font-weight: bold; color: #3498db;">{{ $courses_count ?? 0 }}+</h3>
                        </div>
                        <div>
                            <img src="{{ asset('Teacher/assets/img/icons/dash-icon-04.svg') }}" alt="Dashboard Icon" style="height: 50px; width: 70px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FullCalendar Container -->
        <div class="table-responsive">
            <div id="calendar"></div>
        </div>
    </div>
</div>

@endsection


<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
<style>
/* Make sure the calendar is fully responsive */
#calendar {
    max-width: 100%;
    margin: auto;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    overflow-x: auto; /* Enables horizontal scroll if needed */
}

    /* Add a smooth hover effect to dashboard cards */
.mycard {
    transition: all 0.3s ease-in-out;
}

.mycard:hover {
    transform: scale(1.05); /* Slightly enlarge card */
    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
}


</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth', // Month view
        locale: 'ar', // Arabic support
        timeZone: 'Asia/Riyadh',
        events: '/teacher/sessions', // Fetch events from route
        eventClick: function(info) {
            alert('جلسة: ' + info.event.title);
        }
    });

    calendar.render();
});
</script>
