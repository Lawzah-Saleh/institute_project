@extends('admin.layouts.app')

@section('title', 'تعديل السعر')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">تعديل السعر</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('course-prices.index') }}">أسعار الدورات</a></li>
                        <li class="breadcrumb-item active">تعديل السعر</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('course-prices.update', $price->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="course_id">اختر الدورة:</label>
                                        <select name="course_id" id="course_id" class="form-control" required>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}" {{ $price->course_id == $course->id ? 'selected' : '' }}>
                                                    {{ $course->course_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>السعر:</label>
                                        <input type="number" name="price" class="form-control" value="{{ $price->price }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>تفاصيل السعر:</label>
                                        <input type="number" name="price_details" class="form-control" value="{{ $price->price_details }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>تاريخ السعر:</label>
                                        <input type="date" name="date" class="form-control" value="{{ $price->date }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>تاريخ الموافقة:</label>
                                        <input type="date" name="price_approval" class="form-control" value="{{ $price->price_approval }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>الحالة:</label>
                                        <select name="state" class="form-control" required>
                                            <option value="1" {{ $price->state ? 'selected' : '' }}>نشط</option>
                                            <option value="0" {{ !$price->state ? 'selected' : '' }}>غير نشط</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary">تحديث السعر</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
