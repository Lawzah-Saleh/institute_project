@extends('dashboard-Student.layouts.app')

@section('title', ' Student Dashboard')

@section('content')



{{-- ========================= --}}
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                <h3 class="page-title" style="color: #ff9800; display: flex; align-items: center; font-family: 'Roboto', sans-serif; font-size: 1.2rem;">
                    <i class="fas fa-user-graduate" style="margin-right: 15px; color: #ff9800; font-size: 1.2rem;"></i>
                    مرحبًا بك، أيها الطالب!
                </h3>
                <ul class="breadcrumb" style="margin-top: 10px;">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #ff9800; font-size: 1rem;">الصفحة الرئيسية</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Dynamic Course Cards -->
    <div id="courses-container" class="col-12 d-flex flex-wrap justify-content-between" style="gap: 15px;">
        <!-- Cards will be generated dynamically -->
    </div>
</div>

<script>
    // Example data for courses (This should be fetched dynamically in real scenarios)
    const courses = [
        { id: 1, name: "رياضيات 101", description: "أساسيات الرياضيات", progress: 50 },
        { id: 2, name: "فيزياء 201", description: "قوانين الفيزياء الأساسية", progress: 70 },
        { id: 3, name: "برمجة 301", description: "مقدمة في البرمجة بلغة JavaScript", progress: 30 }
    ];

    const coursesContainer = document.getElementById('courses-container');

    // Generate cards dynamically
    courses.forEach(course => {
        const card = document.createElement('div');
        card.className = 'col-xl-3 col-lg-4 col-md-6 d-flex';

        card.innerHTML = `
            <div class="card w-100 shadow-sm" style="background: white; border-radius: 12px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body">
                    <div class="db-widgets d-flex flex-column align-items-start">
                        <h6 style="font-size: 1.2rem; color: #ff9800; font-family: 'Roboto', sans-serif;">${course.name}</h6>
                        <p style="color: #ff9800; font-size: 0.9rem; font-family: 'Roboto', sans-serif;">${course.description}</p>
                        <div style="width: 100%; margin-top: 20px;">
                            <div class="progress" style="height: 20px; background-color: rgba(0, 0, 0, 0.1);">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: ${course.progress}%" aria-valuenow="${course.progress}" aria-valuemin="0" aria-valuemax="100">${course.progress}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        coursesContainer.appendChild(card);
    });
</script>

<style>
    body {
        background: linear-gradient(135deg, #f0f8ff, #e6e6fa);
        margin: 0;
        padding: 0;
        font-family: 'Roboto', sans-serif;
    }

    .card {
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        color: #333;
        margin: 10px 0;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .progress-bar {
        font-size: 0.9rem;
        font-weight: bold;
        background-color: #ffa726;
    }

    .breadcrumb-item a {
        color: #ff9800;
        text-decoration: none;
    }

    #courses-container { gap: 10px;
    }

    .page-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #ff9800;
    }
</style>


@endsection
