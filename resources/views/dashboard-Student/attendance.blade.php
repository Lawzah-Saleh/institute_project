@extends('dashboard-Student.layouts.app')

@section('title', 'نسبة الحضور والغياب')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
                <h3 class="page-title" style="color: #196098; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                    <i class="fas fa-user-check" style="margin-left: 15px; color: #196098; font-size: 1.2rem;"></i>
                    نسبة الحضور والغياب
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: right;">
            <div class="card-body">
                <h4 style="color: #333;">اختر الدورة</h4>
                <select id="courseSelect" class="form-control">
                    <option selected disabled>اختر الدورة</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                    @endforeach
                </select>
                
                
                <div id="attendanceInfo" class="hidden" style="margin-top: 20px;">
                    <div class="card blue selectable" style="background-color: #196098; color: white; padding: 15px; border-radius: 8px; text-align: center; cursor: pointer; transition: transform 0.3s ease-in-out;">إجمالي الأيام: <span id="totalDays">0</span> يوم</div>
                    <div class="card green selectable" id="attendanceCard" style="background-color: #1D6F42; color: white; padding: 15px; border-radius: 8px; text-align: center; margin-top: 10px; cursor: pointer; transition: transform 0.5s ease-in-out;">أيام الحضور: <span id="attendanceDays">0</span></div>
                    <div class="card red selectable" style="background-color:  #7D1A1A; color: white; padding: 15px; border-radius: 8px; text-align: center; margin-top: 10px; cursor: pointer; transition: transform 0.3s ease-in-out;"> أيام الغياب : <span id="absenceDays">0</span>
                </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #f0f8ff, #e6e6fa);
        margin: 0;
        padding: 0;
        font-family: 'Roboto', sans-serif;
        direction: rtl;
    }
    .card {
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        color: #333;
        margin: 10px 0;
    }
    .page-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #196098;
    }
    .hidden {
        display: none;
    }
    .selectable:hover {
        transform: scale(1.05);
    }
    .selected {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>



<script>
document.getElementById('courseSelect').addEventListener('change', function () {
    let courseId = this.value;
    console.log('Selected course ID:', courseId); // ✅ Check what’s selected

    fetch(`/student/attendance/data/${courseId}`)
        .then(res => res.json())
        .then(data => {
            console.log('Attendance Data:', data); // ✅ See what is returned

            document.getElementById('totalDays').innerText = data.totalDays;
            document.getElementById('attendanceDays').innerText = data.attendanceDays;
            document.getElementById('absenceDays').innerText = data.absenceDays;
            document.getElementById('attendanceInfo').style.display = 'block';
        })
        .catch(error => {
            console.error('Fetch Error:', error); // ❌ Display fetch error
        });
});


</script>
@endsection
