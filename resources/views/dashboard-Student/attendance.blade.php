{{-- 
{{-- ========================================================= --}}
@extends('dashboard-Student.layouts.app')

@section('title', 'الحضور والغياب')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: center;">
                <h3 class="page-title" style="color: #4CAF50; font-family: 'Roboto', sans-serif; font-size: 1.4rem;">
                    <i class="fas fa-calendar-alt" style="margin-left: 10px; color: #4CAF50; font-size: 1.4rem;"></i>
                    تقويم الحضور والغياب - ديسمبر
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="d-flex align-items-center justify-content-center" style="height: calc(100vh - 150px);">
    <div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); text-align: center; width: 80%;">
        <div class="card-body">
            <div class="calendar">
                <h1 style="font-size: 1.6rem; font-family: 'Roboto', sans-serif; color: #333;">تقويم الحضور</h1>
                <table class="table table-bordered" style="width: 100%; margin: 0 auto; text-align: center; direction: rtl;">
                    <thead style="background: #FFA500; color: white;">
                        <tr>
                            <th>الأحد</th>
                            <th>الاثنين</th>
                            <th>الثلاثاء</th>
                            <th>الأربعاء</th>
                            <th>الخميس</th>
                            <th>الجمعة</th>
                            <th>السبت</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                            <td>6</td>
                            <td class="weekend" style="background-color: #f0f8ff;">7</td>
                            <td class="weekend" style="background-color: #f0f8ff;">8</td>
                            <td>9</td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>11</td>
                            <td>12</td>
                            <td>13</td>
                            <td class="weekend" style="background-color: #f0f8ff;">14</td>
                            <td class="weekend" style="background-color: #f0f8ff;">15</td>
                            <td>16</td>
                        </tr>
                        <tr>
                            <td>17</td>
                            <td>18</td>
                            <td>19</td>
                            <td class="holiday" style="background-color: #d4edda; color: #155724;">20</td>
                            <td class="weekend" style="background-color: #f0f8ff;">21</td>
                            <td class="weekend" style="background-color: #f0f8ff;">22</td>
                            <td>23</td>
                        </tr>
                        <tr>
                            <td>24</td>
                            <td>25</td>
                            <td>26</td>
                            <td>27</td>
                            <td class="weekend" style="background-color: #f0f8ff;">28</td>
                            <td class="weekend" style="background-color: #f0f8ff;">29</td>
                            <td>30</td>
                        </tr>
                    </tbody>
                </table>
                <div class="legend" style="margin-top: 20px; display: flex; justify-content: center; gap: 15px;">
                    <div style="display: flex; align-items: center;">
                        <span style="display: inline-block; width: 20px; height: 20px; background-color: #f0f8ff; border-radius: 50%; margin-right: 10px;"></span>
                        <span>عطلة نهاية الأسبوع</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <span style="display: inline-block; width: 20px; height: 20px; background-color: #d4edda; border-radius: 50%; margin-right: 10px;"></span>
                        <span>إجازة</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <span style="display: inline-block; width: 20px; height: 20px; background-color: #ffcccb; border-radius: 50%; margin-right: 10px;"></span>
                        <span>غياب</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #e6f7ff, #e6e6fa);
        font-family: 'Roboto', sans-serif;
    }

    .table th {
        text-align: center;
        font-weight: bold;
        background-color: #FFA500 !important; /* برتقالي */
        color: white !important;
    }

    .table td {
        text-align: center;
        vertical-align: middle;
    }

    .absent {
        background-color: #ffcccb !important;
        color: #a94442;
        font-weight: bold;
    }

    .holiday {
        background-color: #d4edda !important;
        color: #155724;
        font-weight: bold;
    }

    .weekend {
        background-color: #f0f8ff !important;
        font-weight: bold;
    }

    .d-flex {
        display: flex !important;
    }

    .align-items-center {
        align-items: center !important;
    }

    .justify-content-center {
        justify-content: center !important;
    }
</style>

@endsection --}}
 {{-- ////////////////////////// --}}
 {{{-- ========================================================= --}}
@extends('dashboard-Student.layouts.app')

@section('title', 'الحضور والغياب')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-sub-header" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); direction: rtl; text-align: center;">
                <h3 class="page-title" style="color: #4CAF50; font-family: 'Roboto', sans-serif; font-size: 1.4rem;">
                    <i class="fas fa-calendar-alt" style="margin-left: 10px; color: #4CAF50; font-size: 1.4rem;"></i>
                    تقويم الحضور والغياب - ديسمبر
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="d-flex align-items-center justify-content-center" style="height: calc(100vh - 150px);">
    <div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); text-align: center; width: 80%;">
        <div class="card-body">
            <div class="calendar">
                <h1 style="font-size: 1.6rem; font-family: 'Roboto', sans-serif; color: #333;">تقويم الحضور</h1>
                <table class="table table-bordered" style="width: 100%; margin: 0 auto; text-align: center; direction: rtl;">
                    <thead style="background: #196098; color: white;">
                        <tr>
                            <th>الأحد</th>
                            <th>الاثنين</th>
                            <th>الثلاثاء</th>
                            <th>الأربعاء</th>
                            <th>الخميس</th>
                            <th>الجمعة</th>
                            <th>السبت</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                            <td>6</td>
                            <td class="weekend" style="background-color: #f0f8ff;">7</td>
                            <td class="weekend" style="background-color: #f0f8ff;">8</td>
                            <td>9</td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>11</td>
                            <td>12</td>
                            <td>13</td>
                            <td class="weekend" style="background-color: #f0f8ff;">14</td>
                            <td class="weekend" style="background-color: #f0f8ff;">15</td>
                            <td>16</td>
                        </tr>
                        <tr>
                            <td>17</td>
                            <td>18</td>
                            <td>19</td>
                            <td class="holiday" style="background-color: #d4edda; color: #155724;">20</td>
                            <td class="weekend" style="background-color: #f0f8ff;">21</td>
                            <td class="weekend" style="background-color: #f0f8ff;">22</td>
                            <td>23</td>
                        </tr>
                        <tr>
                            <td>24</td>
                            <td>25</td>
                            <td>26</td>
                            <td>27</td>
                            <td class="weekend" style="background-color: #f0f8ff;">28</td>
                            <td class="weekend" style="background-color: #f0f8ff;">29</td>
                            <td>30</td>
                        </tr>
                    </tbody>
                </table>
                <div class="legend" style="margin-top: 20px; display: flex; justify-content: center; gap: 15px;">
                    <div style="display: flex; align-items: center;">
                        <span style="display: inline-block; width: 20px; height: 20px; background-color: #f0f8ff; border-radius: 50%; margin-right: 10px;"></span>
                        <span>عطلة نهاية الأسبوع</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <span style="display: inline-block; width: 20px; height: 20px; background-color: #d4edda; border-radius: 50%; margin-right: 10px;"></span>
                        <span>إجازة</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <span style="display: inline-block; width: 20px; height: 20px; background-color: #ffcccb; border-radius: 50%; margin-right: 10px;"></span>
                        <span>غياب</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #e6f7ff, #e6e6fa);
        font-family: 'Roboto', sans-serif;
    }

    .table th {
        text-align: center;
        font-weight: bold;
        background-color: #196098 !important; /* تم تغيير اللون */
        color: white !important;
    }

    .table td {
        text-align: center;
        vertical-align: middle;
    }

    .absent {
        background-color: #ffcccb !important;
        color: #a94442;
        font-weight: bold;
    }

    .holiday {
        background-color: #d4edda !important;
        color: #155724;
        font-weight: bold;
    }

    .weekend {
        background-color: #f0f8ff !important;
        font-weight: bold;
    }

    .d-flex {
        display: flex !important;
    }

    .align-items-center {
        align-items: center !important;
    }

    .justify-content-center {
        justify-content: center !important;
    }
</style>

@endsection
