@extends('admin.layouts.app')

@section('title', 'تسجيل الحضور')

@section('content')
<div class="container">
    <h3>تسجيل الحضور للجلسة: {{ $session->course->course_name }}</h3>
    <form action="{{ route('attendance.mark') }}" method="POST">
        @csrf
        <input type="hidden" name="session_id" value="{{ $session->id }}">
        <table class="table">
            <thead>
                <tr>
                    <th>الطالب</th>
                    <th>حضور</th>
                    <th>غياب</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                <tr>
                    <td>{{ $student->student_name_ar }}</td>
                    <td>
                        <input type="radio" name="attendances[{{ $student->id }}][status]" value="1">
                    </td>
                    <td>
                        <input type="radio" name="attendances[{{ $student->id }}][status]" value="0">
                    </td>
                    <input type="hidden" name="attendances[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-success">حفظ الحضور</button>
    </form>
</div>
@endsection
