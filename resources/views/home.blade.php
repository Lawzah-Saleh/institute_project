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
              <div>
                <h4 class="title"><a href="#" class="stretched-link">لتعليم أفضل</a></h4>
                <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi</p>
              </div>
            </div>
          </div>
          <!-- End Service Item -->

          <div class="col-xl-4 col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item d-flex">
              <div class="icon flex-shrink-0"><i class="bi bi-card-checklist"></i></div>
              <div>
                <h4 class="title"><a href="#" class="stretched-link">مدرسين ذو خبرة</a></h4>
                <p class="description">Minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip exa</p>
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-4 col-lg-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item d-flex">
              <div class="icon flex-shrink-0"><i class="bi bi-bar-chart"></i></div>
              <div>
                <h4 class="title"><a href="#" class="stretched-link">عدة مجالات</a></h4>
                <p class="description">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum</p>
              </div>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Featured Services Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
            <p class="who-we-are">من نحن</p>
            <h3>{{$institute->institute_name}}</h3>
            <p class="fst-italic"> {{ $institute->about_us }}            </p>
          </div>

          <div class="col-lg-6 about-images" data-aos="fade-up" data-aos-delay="200">
            <div class="row gy-4">
              <div class="col-lg-6">
                <img src="assets/img/about-company-1.jpg" class="img-fluid" alt="">
              </div>
              <div class="col-lg-6">
                <div class="row gy-4">
                  <div class="col-lg-12">
                    <img src="assets/img/about-company-2.jpg" class="img-fluid" alt="">
                  </div>
                  <div class="col-lg-12">
                    <img src="assets/img/about-company-3.jpg" class="img-fluid" alt="">
                  </div>
                </div>
              </div>
            </div>

          </div>

        </div>

      </div>
    </section><!-- /About Section -->
    <section id="features-details" class="features-details section">
            <div class="container section-title" data-aos="fade-up">
                <h2>الأعلانات </h2>
                <p>هنا تجد أحدث الإعلانات الخاصة بالمعهد</p>
            </div>

        <div class="container">
            @foreach ($advertisements as $advertisement)
            <div class="row gy-4 justify-content-between features-item">

                <div class="col-lg-5 d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="content">
                    <h3>{{ $advertisement->title }} </h3>
                    <p>{{ $advertisement->text }}</p>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <img src="assets/img/{{ $advertisement->image }}" class="img-fluid" alt="">

                </div>

            </div><!-- Features Item -->
            @endforeach
        </div>

    </section><!-- /Features Details Section -->

    <!-- Services Section -->
    <section id="services" class="services section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up" >
        <h2>الأقسام</h2>
        <p>لمعرفة الدورات اللي بكل قسم اضغط على القسم وبيضهر الكورسات المتاحة في القسم</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row g-5">
            @foreach ($departments as $department)

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item item-cyan position-relative">
              <i class="bi bi-activity icon"></i>
              <div>
                <h3>{{ $department->department_name }}</h3>
                <p>{{ $department->department_info }}</p>

                </div>
            </div>
          </div><!-- End Service Item -->

          @endforeach
          <div class="container text-center mt-5">
            <a href="{{ url('/courses') }}" style=" background-color: #196098; border: none; padding: 15px 30px;font-size: 18px; border-radius: 50px; transition: all 0.3s ease;     color: #fff; ">معرفة المزيد</a>
        </div>


        </div>

      </div>

    </section><!-- /Services Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>أتصل بنا </h2>
        <p>للتواصل معنا او يمكنك زيارة المعهد في اي وقت </p>
      </div><!-- End Section Title -->


        <div class="row gy-4">

            <div class="col-lg-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
                <i class="bi bi-geo-alt"></i>
                <h3>العنوان</h3>
                <p>{{ $institute->address }}</p>
                </div>
            </div><!-- End Info Item -->

            <div class="col-lg-3 col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
                <i class="bi bi-telephone"></i>
                <h3>تلفون</h3>
                <p>{{ $institute->phone }}</p>
                </div>
            </div><!-- End Info Item -->

            <div class="col-lg-3 col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
                <i class="bi bi-envelope"></i>
                <h3> الأيميل</h3>
                <p>{{ $institute->email }}</p>
                </div>
            </div><!-- End Info Item -->

        </div>


    </section><!-- /Contact Section -->

    @endsection
