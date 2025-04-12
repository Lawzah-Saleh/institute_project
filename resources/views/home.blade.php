@extends('layouts.app')

@section('title', 'الصفحة الرئيسية')

@section('content')


    <!-- Hero Section -->
    <section id="hero" class="hero section">
      <div class="hero-bg">
        <img src="assets/img/hero-bg-light.webp" alt="">
      </div>
      <div class="container text-center">
        <div class="d-flex flex-column justify-content-center align-items-center">
          <h1 data-aos="fade-up">  مرحباً بكم <span> {{ $institute->institute_name }} </span></h1>
          <p data-aos="fade-up" data-aos-delay="100"> {{ $institute->institute_description }} <br></p>
          <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('students.register.form') }}" class="btn-get-started">أبدا </a>
          </div>
          <img src="assets/img/hero-services-img.webp" class="img-fluid hero-img" alt="" data-aos="zoom-out" data-aos-delay="300">
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- Featured Services Section -->
    <section id="featured-services" class="featured-services section light-background">

        <div class="container">

            <div class="row gy-4">
    
              <div class="col-xl-4 col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="service-item d-flex">
                  <div class="icon flex-shrink-0"><i class="bi bi-briefcase"></i></div>
                  <div  style="margin-right:20px">
                    <h4 class="title"><a href="#" class="stretched-link">لتعليم أفضل</a></h4>
                    <p class="description">"نؤمن أن التعليم الحقيقي يُغيّر الحياة."<br>
                      نصنع فرقًا من خلال جودة المحتوى، وعمق الفهم، وحداثة الأسلوب.
                      
                      </p>
                  </div>
                </div>
              </div>
              <!-- End Service Item -->
    
              <div class="col-xl-4 col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="service-item d-flex">
                  <div class="icon flex-shrink-0"><i class="bi bi-card-checklist"></i></div>
                  <div  style="margin-right:20px">
                    <h4 class="title"><a href="#" class="stretched-link">مدرسين ذو خبرة</a></h4>
                    <p class="description"> "مع الخبرة... يصبح التعلم رحلة نجاح."<br>
                      مدربونا خبراء في تخصصاتهم، ينقلون المعرفة بشغف واحترافية.</p>
                  </div>
                </div>
              </div><!-- End Service Item -->
    
              <div class="col-xl-4 col-lg-6" data-aos="fade-up" data-aos-delay="300">
                <div class="service-item d-flex">
                  <div class="icon flex-shrink-0"><i class="bi bi-bar-chart" ></i></div>
                  <div style="margin-right:20px">
                    <h4 class="title"><a href="#" class="stretched-link">عدة مجالات</a></h4>
                    <p class="description">"مكان واحد..لفرص لا حدود لها."<br>
                      نقدم مسارات تعليمية متنوعة تفتح لك أبواب التخصص والإبداع.
                      </p>
                  </div>
                </div>
              </div><!-- End Service Item -->
    
            </div>
    
          </div>
    

    </section><!-- /Featured Services Section -->

  <!-- About Section -->
<section id="about" class="about section">
    <div class="container">
      <div class="row align-items-center gy-5">
  
        <!-- النص -->
        <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
          <div class="content">
            <h5 class="text-primary mb-2"><strong>من نحن</strong>  </h5>
            <p class="text-muted" style="font-size: 20px; line-height: 1.9;">
              {{ $institute->about_us }}
            </p>
          </div>
        </div>
  
        <!-- الصورة -->
        <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
            <img src="{{ asset('storage/' . $institute->about_image) }}" class="img-fluid rounded-4 shadow" alt="عن المعهد">
        </div>
  
      </div>
    </div>
  </section>
  <section id="features-details" class="features-details section">
    <div class="container section-title" data-aos="fade-up">
        <h2>الإعلانات</h2>
        <p>هنا تجد أحدث الإعلانات الخاصة بالمعهد</p>
    </div>

    <div class="container">
        @foreach ($advertisements as $advertisement)
            <div class="row gy-4 align-items-center flex-row-reverse mb-5 features-item" dir="rtl">

                <!-- الصورة -->
                <div class="col-md-5 text-center" data-aos="fade-up" data-aos-delay="100">
                    <img src="{{ asset('storage/' . $advertisement->image) }}"
                         class="img-fluid shadow-sm"
                         style="max-width: 100%; height: auto; max-height: 300px; border-radius: 20px;"
                         alt="{{ $advertisement->title }}">
                </div>

                <!-- النص -->
                <div class="col-md-7" data-aos="fade-up" data-aos-delay="200">
                    <div class="content bg-light p-4 rounded shadow-sm">
                        <h4 class="fw-bold mb-3">{{ $advertisement->title }}</h4>
                        <p class="mb-0 text-muted" style="line-height: 1.8;">
                            {{ $advertisement->content }}
                        </p>
                    </div>
                </div>

            </div>
        @endforeach
    </div>
</section>



<section id="departments" class="py-5 bg-white">
    <div class="container text-center mb-5" data-aos="fade-up">
        <h2 class="fw-bold">الأقسام التعليمية</h2>
        <p class="text-muted">استعرض الأقسام التعليمية وتعرف على الدورات التي نقدمها في كل تخصص.</p>
    </div>

    <div class="container">
        <div class="row g-4">
            @foreach ($departments as $department)
                <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden department-card transition">
                        <div class="card-body d-flex flex-column justify-content-between text-center p-4">

                            <!-- أيقونة أو رمز القسم -->
                            <div class="icon-wrapper mb-3 mx-auto rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-book fs-2 text-primary"></i>
                            </div>

                            <!-- عنوان القسم -->
                            <h5 class="fw-bold text-dark mb-2">{{ $department->department_name }}</h5>

                            <!-- وصف القسم -->
                            <p class="text-muted mb-4" style="font-size: 15px; line-height: 1.7;">
                                {{ $department->department_info }}
                            </p>

                            <!-- زر عرض -->
                            <a href="{{ url('/courses?department=' . $department->id) }}" class="btn btn-outline-primary rounded-pill px-4 py-2 mt-auto transition">
                                استعرض الدورات
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>

<section id="contact" class="contact section py-5 bg-light">
    <div class="container">

        <!-- عنوان القسم -->
        <div class="section-title text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold">اتصل بنا</h2>
            <p class="text-muted">للتواصل معنا أو زيارة المعهد في أي وقت</p>
        </div>

        <!-- عناصر الاتصال -->
        <div class="row g-4 justify-content-center">

            <!-- العنوان -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-white shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="mb-3">
                        <i class="bi bi-geo-alt fs-2 text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-2">العنوان</h5>
                    <p class="text-muted mb-0">{{ $institute->address }}</p>
                </div>
            </div>

            <!-- الهاتف -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-white shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="mb-3">
                        <i class="bi bi-telephone fs-2 text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-2">الهاتف</h5>
                    <p class="text-muted mb-0">{{ $institute->phone }}</p>
                </div>
            </div>

            <!-- البريد الإلكتروني -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="bg-white shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="mb-3">
                        <i class="bi bi-envelope fs-2 text-danger"></i>
                    </div>
                    <h5 class="fw-bold mb-2">البريد الإلكتروني</h5>
                    <p class="text-muted mb-0">{{ $institute->email }}</p>
                </div>
            </div>

        </div>
    </div>
</section>


    @endsection
