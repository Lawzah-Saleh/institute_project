@extends('admin.layouts.app')

@section('title', 'تعديل رسوم الطالب')

@section('content')
<div class="page-wrapper" style="background: #f7f7fa; padding: 30px;">
    <div class="content container-fluid">
        <h3 class="page-title mb-4">تعديل رسوم الطالب للدورة</h3>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('payments.update', $payment->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">اسم الطالب:</label>
                        <input type="text" class="form-control" value="{{ $payment->student->student_name_ar }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الدورة:</label>
                        <input type="text" class="form-control" value="{{ $payment->course->course_name ?? 'غير محددة' }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">المبلغ الكلي:</label>
                        <input type="number" name="total_amount" class="form-control" value="{{ old('total_amount', $payment->total_amount) }}" required>
                    </div>

                    <button type="submit" class="btn "style="background-color: #196098;color: white;">تحديث</button>
                    <a href="{{ url()->previous() }}" class="btn "style="background-color: #e94c21;color: white;">رجوع</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
