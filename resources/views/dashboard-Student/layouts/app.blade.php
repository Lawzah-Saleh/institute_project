<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>@yield('title', 'Student Dashboard')</title>
    <link rel="shortcut icon" href="{{ asset('admin/assets/img/efi.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('student/assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('student/assets/plugins/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('student/assets/plugins/icons/flags/flags.css') }}">
    <link rel="stylesheet" href="{{ asset('student/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('student/assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('student/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('student/assets/css/styleme.css') }}">
</head>

<body>
    <div class="main-wrapper">
        @include('dashboard-Student.partials.header') <!-- Include Header -->
        @include('dashboard-Student.partials.sidebar') <!-- Include Sidebar -->

        <div class="page-wrapper">
            <div class="content container-fluid">
                @yield('content') <!-- Page Content -->
            </div>
            <!-- <footer>
                <p>معهد التعليم أولاً          صنعاء - سعوان - جولة النصر</p>
            </footer> -->
        </div>
    </div>

    <script src="{{ asset('student/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('student/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('student/assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('student/assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('student/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('student/assets/plugins/apexchart/chart-data.js') }}"></script>
    <script src="{{ asset('student/assets/js/script.js') }}"></script>
</body>

</html>
