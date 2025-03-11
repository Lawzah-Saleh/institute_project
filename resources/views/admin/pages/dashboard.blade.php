@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div  style="margin-right: 100px;padding:50px 20px;width: calc(105% - 150px);background:#f7f7fa;">
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header">
                <h3 class="page-title">مرحبا</h3>

            </div>
        </div>
    </div>
</div>

<div class="row" style="display: flex;">
    <div class="row" style="display: flex;">
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="mycard">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6> الطلاب</h6>

                            <h3>{{ $studentsCount  ?? 0 }}</h3>
                        </div>
                        <div class="icon_box">
                            <img src="{{ asset('admin/assets/img/icons/dash-icon-01.png') }}" alt="Dashboard Icon" style="height: 70px;width:70px">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="mycard">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>المعلمين</h6>
                            <h3>{{ $teachersCount ?? 0}}</h3>
                        </div>
                        <div class="icon_box">
                            <img src="{{ asset('admin/assets/img/icons/dash-icon-02.png') }}" alt="Dashboard Icon" style="height: 70px;width:70px">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="mycard">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>الأقسام</h6>
                            <h3>{{ $departmentsCount ?? 0}}</h3>
                        </div>
                        <div class="icon_box">
                            <img src="{{asset('admin/assets/img/icons/dash-icon-03.png')}}" alt="Dashboard Icon" style="height: 70px;width:70px">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="mycard">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>الكورسات</h6>
                            <h3>{{ $coursesCount ?? 0}}</h3>
                        </div>
                        <div class="icon_box">
                            <img src="{{asset('admin/assets/img/icons/dash-icon-04.svg')}}" alt="Dashboard Icon" style="height: 50px;width:70px">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<div class="row" style="margin-top: 70px;">

    <div class="col-md-12 col-lg-6">

        <div style="width: 100%; margin: auto;">
            <canvas id="studentsChart"></canvas>
        </div>
        <script>
            // بيانات الطلاب لكل شهر
            const months = ["يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"];
            const totalStudents = [150, 180, 200, 170, 210, 190, 230, 250, 240, 220, 260, 270]; // قم بتعديل الأرقام بناءً على بياناتك.

            // إعداد المخطط
            const ctx = document.getElementById('studentsChart').getContext('2d');
            const studentsChart = new Chart(ctx, {
                type: 'bar', // نوع المخطط
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Total Students',
                        data: totalStudents,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

    </div>
    <div class="col-xl-6 d-flex">









    </div>
</div>
</div>

</div></div></div>

@endsection

<style>
    .mycard {
  background-color: #ffffff;
  border-radius: 40px; /* لتنعيم الزوايا */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  width: 370px; /* عرض البطاقة */
  height: 150px; /* ارتفاع البطاقة */
  text-align: left;
  padding: 20px;
  transition: transform 0.3s ease, box-shadow 0.3s ease; /* تأثير الحركة */
}

.icon_box img {
  width: 50px;
  height: 50px;
  margin-bottom: 10px;
}

.mycard p {
  font-size: 18px;
  font-weight: bold;
}

.mycard span {
  font-size: 24px;
  color: #007bff;
}
.mycard:hover {
  transform: scale(1.1); /* تكبير البطاقة بنسبة 10% */
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* ظل أقوى */
}
.icon_box{
  background-color: #faece8;
  width: 80px; /* عرض الحاوية */
  height: 80px; /* ارتفاع الحاوية */
  margin: 0 auto; /* توسيط الصورة داخل البطاقة */
  overflow: hidden; /* إخفاء أي محتوى زائد عن الحاوية */
  border-radius: 30%; /* اختياريا: لجعل الحاوية دائرية */
  margin-left: 10px;
}
</style>
