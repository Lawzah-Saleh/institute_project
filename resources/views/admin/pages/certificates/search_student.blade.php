@extends('admin.layouts.app')

@section('title', 'بحث عن طالب')

@section('content')
    <div class="page-wrapper" style="margin-right: 100px; padding:50px 20px; width: calc(105% - 150px); background:#f7f7fa;">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">بحث عن طالب</h3>
                    </div>
                </div>
            </div>

            <!-- نموذج البحث -->
            <form method="POST" action="{{ route('student.search.submit') }}" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو البريد أو الهاتف">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">بحث</button>
                    </div>
                </div>
            </form>

            <!-- عرض نتائج البحث -->
            @if(isset($students) && $students->isNotEmpty())
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>اسم الطالب</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الجلسات</th>
                                    <th>إصدار الشهادة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->student_name_ar }} ({{ $student->student_name_en }})</td>
                                        <td>{{ $student->email }}</td>
                                        <td>
                                            <ul>
                                                @foreach($student->sessions as $session)
                                                    <li>{{ $session->course->course_name }} - {{ $session->start_date }} - {{ $session->end_date }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            @foreach($student->sessions as $session)
                                                <a href="{{ route('certificate.generate', ['studentId' => $student->id, 'courseSessionId' => $session->id]) }}" class="btn btn-success">إصدار الشهادة</a>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <p>لا توجد نتائج مطابقة.</p>
            @endif
        </div>
    </div>
@endsection
