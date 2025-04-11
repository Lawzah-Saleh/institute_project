@extends('Teacher-dashboard.layouts.app')

@section('title', 'T-Student')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid" style="background-color: #f9f9fb">

        <!-- ترحيب بالمعلم -->
        <div class="flex justify-center mb-6">
            <div class="bg-white shadow-md rounded-lg p-6 text-center w-full md:w-2/3">
                <h3 class="text-2xl font-bold text-gray-700">مرحبًا أستاذ {{ Auth::user()->name }}</h3>
            </div>
        </div>

        <!-- نموذج البحث -->
        <div class="student-group-form mb-4">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-3">
                    <div class="form-group">
                        <select id="department" class="form-control" style="width: 100%; height: 40px;">
                            <option value="">اختر القسم</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 mb-3">
                    <div class="form-group">
                        <select id="course" class="form-control" style="width: 100%; height: 40px;">
                            <option value="">اختر الكورس</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 mb-3">
                    <div class="form-group">
                        <select id="session" class="form-control" style="width: 100%; height: 40px;">
                            <option value="">اختر الجلسة</option>
                        </select>
                    </div>
                </div>


                <div class="col-lg-20 col-md-12 mb-3 d-flex">

                    <button type="button" class="btn btn-primary ml-2" id="search-button" style="width: 20%; height: 40px; background: #e94c21; font-size: 1.1rem;">عرض الطلاب</button>
                </div>
            </div>
        </div>


        <!-- جدول عرض الطلاب -->
        <div class="table-responsive">
            <table class="table border-0 table-hover table-center mb-0 datatable">
                <thead class="text-white" style="background-color: #196098;">
                    <tr>
                        <th>رقم الطالب</th>
                        <th>اسم الطالب</th>
                        <th>درجة النصفية</th>
                        <th>درجة النهائية</th>
                        <th>درجة الحضور</th>
                        <th>المجموع</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody id="students-table-body">
                    {{-- بيانات افتراضية كمثال - استبدل بـ @foreach عند الربط بالباكند --}}
                    <tr style="background-color: #f9f9f9;">
                        <td>PRE2201</td>
                        <td>أحمد محمد</td>
                        <td>20</td>
                        <td>45</td>
                        <td>10</td>
                        <td>75</td>
                        <td>ناجح</td>
                    </tr>
                    <tr style="background-color: #e2e8f0;">
                        <td>PRE2202</td>
                        <td>سارة خالد</td>
                        <td>18</td>
                        <td>40</td>
                        <td>12</td>
                        <td>70</td>
                        <td>ناجح</td>
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <td>PRE2203</td>
                        <td>ليان عبدالله</td>
                        <td>22</td>
                        <td>44</td>
                        <td>9</td>
                        <td>75</td>
                        <td>ناجح</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
