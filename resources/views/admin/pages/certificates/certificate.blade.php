<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>اصدار شهادة</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #eaeff4;
      font-family: 'Arial', sans-serif;
    }

    .certificate-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .certificate-container {
      width: 1100px;
      background: #fff;
      padding: 50px 60px;
      border: 6px solid #196098;
      border-radius: 15px;
      box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
    }

    .certificate-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 40px;
    }

    .gov-header, .eng-header {
      font-size: 16px;
      font-weight: bold;
      line-height: 1.8;
    }

    .gov-header p, .eng-header p {
      margin: 0;
    }

    .eng-header {
      text-align: left;
      direction: ltr;
      padding-left: -40px;
    }

    .logo-center {
      text-align: center ;
      margin: 0 auto;

    }

    .logo-center img {
  width: 90px;
  height: auto;
  display: block;
  margin: 0 auto;
  position: relative;
}

    .certificate-title {
      text-align: center;
      margin: 30px 0;
    }

    .certificate-title h2 {
      font-size: 26px;
      color: #196098;
      margin-bottom: 5px;
    }

    .certificate-content-dual {
      display: grid;
      grid-template-columns: 1fr 60px 1fr;
      gap: 20px;
      margin-top: 40px;
    }

    .arabic-section {
      text-align: right;
      direction: rtl;
      font-size: 18px;
      line-height: 2.2;
    }

    .english-section {
      text-align: left;
      direction: ltr;
      font-size: 16px;
      line-height: 2.2;
    }

    .field span {
      font-weight: bold;
      text-decoration: underline;
      padding: 0 5px;
    }

    .signature {
      margin-top: 50px;
      text-align: left;
      direction: ltr;
      font-size: 16px;
    }

    .footer {
      text-align: center;
      margin-top: 40px;
      font-style: italic;
      color: #666;
      font-size: 14px;
    }

    @media print {
      body {
        background: none;
      }
      .certificate-container {
        box-shadow: none;
      }
    }
  </style>
</head>
<body>
<div class="certificate-wrapper">
  <div class="certificate-container">

    <!-- Header -->
    <div class="certificate-header">
        <div class="gov-header">
            <p>الجمهورية اليمنية</p>
            <p>وزارة التعليم الفني والتدريب المهني</p>
            <p>معهد التعليم أولاً</p>
          </div>

      <div class="logo-center">
        <img src="{{ asset('assets/img/efi.png') }}" alt="شعار المعهد">
      </div>
      <div class="eng-header">
        <p>Republic of Yemen</p>
        <p>Ministry of Technical Education</p>
        <p>and Vocational Training</p>
        <p>Education First Institute</p>
      </div>
    </div>

    <!-- Title -->
    <div class="certificate-title">
      <h2>شهادة إتمام البرنامج التدريبي</h2>
    </div>

    <!-- Content Side by Side -->
    <div class="certificate-content-dual">
      <div class="arabic-section">
        <div class="field">يشهد معهد التعليم أولاً بأن الطالب/ <span>{{ $student->student_name_ar }}</span></div>
        <div class="field">مكان الميلاد: <span>{{ $student->brth_place }}</span>، تاريخ الميلاد: <span>{{ $student->birth_date }}</span></div>
        <div class="field">الجنسية: <span>{{ $student->nationality ?? 'يمني' }}</span></div>
        <div class="field">قد أكمل دراسة البرنامج التدريبي في دبلوم: <span>{{ $courseSession->course->cours_name }}</span></div>
        <div class="field">عدد الساعات: <span>{{ $courseSession->course->duration }}</span> ساعة</div>
        <div class="field">وقد اجتاز الامتحانات النهائية بنجاح، وبناءً عليه مُنحت له هذه الشهادة.</div>
        <div class="field">بتقدير عام: <span>{{ $degree->final_degree }}</span>، بنسبة: <span>{{ $degree->percentage }}%33</span></div>
        <div class="field">من: <span>{{ $courseSession->start_date }}</span> إلى: <span>{{ $courseSession->end_date }}</span></div>
      </div>
      <div class="logo-spacer"></div>
      <div class="english-section">
        <div class="field">This is to certify that student: <span>{{ $student->student_name_en }}</span></div>
        <div class="field">Place of Birth: <span>{{ $student->brth_place }}</span>, Date of Birth: <span>{{ $student->birth_date }}</span></div>
        <div class="field">Nationality: <span>{{ $student->nationality ?? 'Yemeni' }}</span></div>
        <div class="field">Has successfully completed the training program in: <span>{{ $courseSession->course->course_name_en }}</span></div>
        <div class="field">Duration: <span>{{ $courseSession->course->duration }}</span> hours</div>
        <div class="field">And has passed the final examinations successfully, therefore this certificate is awarded.</div>
        <div class="field">Overall Grade: <span>{{ $degree->final_degree }}</span>, Percentage: <span>{{ $degree->percentage }}%33</span></div>
        <div class="field">From: <span>{{ $courseSession->start_date }}</span> To: <span>{{ $courseSession->end_date }}</span></div>
      </div>
    </div>

    <!-- Signature -->
    <div class="signature">
      توقيع مدير المعهد<br>
      Date: {{ now()->format('Y-m-d') }}
    </div>

    <!-- Footer -->
    <div class="footer">
      This certificate is issued by Education First Institute as official proof of successful completion.
    </div>
  </div>
</div>

<div style="text-align: center; margin-top: 20px;">
  <button onclick="window.print()" style="padding: 10px 30px; font-size: 16px; background-color: #196098; color: white; border: none; border-radius: 8px; cursor: pointer;">
    🖸️ طباعة الشهادة
  </button>
</div>
</body>
</html>