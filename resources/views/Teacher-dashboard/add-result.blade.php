
@extends('Teacher-dashboard.layouts.app')

@section('title', 'add-result')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">النتائج</h3>
                        {{-- <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">لوجة التحكم</a></li>
                            <li class="breadcrumb-item ">النتائج</a></li>

                        </ul> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="student-group-form mb-4">
            <div class="row">

                <div class="col-lg-6 col-md-12 mb-3">


                    <!-- Dropdown للكورسات -->
                    <div class="form-group">
                        <label>الكورسات</label>
                        <select id="course" class="form-control">
                            <option value="">اختر الكورس</option>
                        </select>
                    </div>
                </div>

                <!-- البحث برقم الطالب أو الاسم -->
                <div class="col-lg-6 col-md-12 mb-3">

                    <div class="form-group">
                        <input type="text" id="search-name" class="form-control" placeholder="البحث بالاسم...">
                    </div>
                    <div class="search-student-btn mt-2">
                        <button type="button" class="btn btn-primary w-100" id="search-button" style="background: #e94c21">Search</button>
                    </div>
                </div>
            </div>
        </div>



























                    <div class="table-responsive">
                        <table
                            class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                            <thead class="student-thread">
                                <tr>
                                    <th>
                                        <!-- <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox"
                                                    value="something">
                                            </div> -->
                                    </th>
                                    <th>الرقم</th>
                                    <th>الاسم</th>
                                    <th>الكورس</th>
                                    <th>النصفي</th>
                                    <th>النهائي</th>
                                    <th>المجموع</th>
                                    <th>النسبة</th>


                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <!-- <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox"
                                                    value="something">
                                            </div> -->
                                    </td>
                                    <td>PRE2209</td>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="" class="avatar avatar-sm me-2"><img
                                                    class="avatar-img rounded-circle"
                                                    src="{{ asset('Teacher/assets/img/profiles/profile-s.jpg') }}"
                                                    alt="User Image"></a>
                                            <a href="">Aaliyah</a>
                                        </h2>
                                    </td>
                                    <td>

                                        <form>
                                            <input type="text" class="" placeholder=""
                                                style="width: 30% ;border: none">

                                        </form>
                                    </td>
                                    <td>
                                        <form>
                                            <input type="text" class="" placeholder=""
                                                style="width: 30% ;border: none">

                                        </form>
                                    </td>
                                    <td>
                                        <form>
                                            <input type="text" class="" placeholder=""
                                                style="width: 30% ;border: none">

                                        </form>
                                    </td>
                                    <td>  <form>
                                        <input type="text" class="" placeholder="" style="width: 30% ;border: none">

                                    </form></td>
                                    <td>  <form>
                                        <input type="text" class="" placeholder="" style="width: 30% ;border: none">

                                    </form></td>




                                </tr>
                                <tr>
                                    <td>
                                        <!-- <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox"
                                                    value="something">
                                            </div> -->
                                    </td>
                                    <td>PRE2213</td>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="" class="avatar avatar-sm me-2"><img
                                                    class="avatar-img rounded-circle"
                                                    src="{{ asset('Teacher/assets/img/profiles/profile-s.jpg') }}"
                                                    alt="User Image"></a>
                                            <a href="">Malynne</a>
                                        </h2>
                                    </td>
                                    <td>  <form>
                                        <input type="text" class="" placeholder="" style="width: 30% ;border: none">

                                    </form> </td>
                                    <td>   <form>
                                        <input type="text" class="" placeholder="" style="width: 30% ;border: none">

                                    </form></td>
                                    <td>  <form>
                                        <input type="text" class="" placeholder="" style="width: 30% ;border: none">

                                    </form></td>
                                    <td>  <form>
                                        <input type="text" class="" placeholder="" style="width: 30% ;border: none">

                                    </form></td>
                                    <td>  <form>
                                        <input type="text" class="" placeholder="" style="width: 30% ;border: none">

                                    </form></td>




                                </tr>
                                <tr>
                                    <td>

                                    <td>



                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</div>

</div>



@endsection
