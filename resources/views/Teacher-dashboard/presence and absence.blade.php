@extends('Teacher-dashboard.layouts.app')

@section('title', 'presence and absence')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid" style="background-color:f9f9fb" >

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">الحضور والغياب</h3>
                        {{-- <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">لوحة التحكم</a></li>
                            <li class="breadcrumb-item ">الحضور والغياب</a></li>

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

                                        </th>
                                        <th>الرقم</th>
                                        <th>الاسم</th>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>5</th>
                                        <th>6</th>
                                        <th>7</th>



                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>


                                        </td>
                                        <td>PRE2209</td>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="" class="avatar avatar-sm me-2">
                                                    <img
                                                        class="avatar-img rounded-circle"
                                                        src="{{ asset('Teacher/assets/img/profiles/profile-s.jpg') }}"
                                                        alt="User Image"></a>
                                                <a href="">Aaliyah</a>
                                            </h2>
                                        </td>
                                        <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" style="margin: 10%;
                                                value=" something">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" style="margin: 10%;
                                                value=" something">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" style="margin: 10%;
                                                value="something">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" style="margin: 10%;
                                                value=" something">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" style="margin: 10%;
                                                value=" something">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" style="margin: 10%;
                                                value=" something">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" style="margin: 10%;
                                                value=" something">
                                            </div>
                                        </td>



                                    </tr>
                                    <tr>
                                        <td>


                                        </td>
                                        <td>PRE2213</td>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="" class="avatar avatar-sm me-2">
                                                    <img class="avatar-img rounded-circle" src="{{ asset('Teacher/assets/img/profiles/profile-s.jpg') }}"


                                                        alt="User Image"></a>
                                                <a href="">Malynne</a>
                                            </h2>
                                        </td>
                                        <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" style="margin: 10%;
                                                value=" something">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" style="margin: 10%;
                                                value=" something">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" style="margin: 10%;
                                                value=" something">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" style="margin: 10%;
                                                value=" something">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" style="margin: 10%;
                                                value=" something">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox"
                                                    style="margin: 10%;" value="something">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" style="margin: 10%;
                                                value=" something">
                                            </div>
                                        </td>



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
