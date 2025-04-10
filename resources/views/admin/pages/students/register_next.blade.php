@extends('admin.layouts.app')

@section('title', 'تسجيل الطالب في الدورة التالية')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <h3 class="page-title">تسجيل الطالب في الدورة التالية</h3>
        </div>

        <form method="GET" action="{{ route('students.register_next_search') }}">
            <input type="text" name="search" class="form-control" placeholder="ابحث عن الطالب بالاسم أو الرقم...">
        </form>
    </div>
</div>
@endsection
