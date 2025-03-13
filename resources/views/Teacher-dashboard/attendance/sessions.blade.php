@extends('admin.layouts.app')

@section('title', 'جلسات المدرس')

@section('content')
<div class="container">
    <h3>الجلسات المتاحة</h3>
    <table class="table">
        <thead>
            <tr>
                <th>الكورس</th>
                <th>التاريخ</th>
                <th>الوقت</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sessions as $session)
            <tr>
                <td>{{ $session->course->course_name }}</td>
                <td>{{ $session->start_date }} - {{ $session->end_date }}</td>
                <td>{{ $session->start_time }} - {{ $session->end_time }}</td>
                <td>
                    <a href="{{ route('attendance.session', $session->id) }}" class="btn btn-primary">تسجيل الحضور</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
