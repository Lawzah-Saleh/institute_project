
@extends('Teacher-dashboard.layouts.app')

@section('title', 'courses')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">


        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">


                    <div class="page-sub-header">
                        <h3 class="page-title">الكورسات</h3>
                        {{-- <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item ">Course</a></li>

                        </ul> --}}
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">

                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">






                                        <main class="flex-1 p-6">

                                            <div class="grid grid-cols-3 gap-4 mb-6">
                                                <div class="bg-orange-200 p-4 rounded-lg">Technology <a
                                                        href="students.html">
                                                        <h5 style="color: blue; font:size 400%;">viwe student
                                                        </h5>
                                                    </a></div>

                                                <div class="bg-yellow-200 p-4 rounded-lg">Artificial
                                                    Intelligence<a href="students.html">
                                                        <h5 style="color: white; font: size 5px;;">viwe student
                                                        </h5>
                                                    </a></div>
                                                <div class="bg-green-200 p-4 rounded-lg">Business Management<a
                                                        href="students.html">
                                                        <h5 style="color: white; font: size 5px;;">viwe student
                                                        </h5>
                                                    </a></div>
                                                <div class="bg-blue-200 p-4 rounded-lg">UX Design<a
                                                        href="students.html">
                                                        <h5 style="color: white; font: size 5px;;">viwe student
                                                        </h5>
                                                    </a></div>
                                                <div class="bg-purple-200 p-4 rounded-lg">Graphics<a
                                                        href="students.html">
                                                        <h5 style="color: white; font: size 5px;;">viwe student
                                                        </h5>
                                                    </a></div>
                                                <div class="bg-pink-200 p-4 rounded-lg">Artificial
                                                    Intelligence<a href="students.html">
                                                        <h5 style="color: white; font: size 5px;;">viwe student
                                                        </h5>
                                                    </a></div>
                                            </div>
                                        </main>
                                    </h3>
                                </div>

                            </div>
                        </div>

                        <div class="table-responsive">


                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</div>

@endsection

