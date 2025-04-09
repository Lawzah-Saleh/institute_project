@extends('admin.layouts.app')

@section('title', 'تعديل الفاتورة')

@section('content')
<div class="page-wrapper" style="background: #f8f9fa; padding: 30px;">
    <div class="content container-fluid">
        <h3 class="page-title mb-4">تعديل بيانات الفاتورة</h3>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>الاسم:</label>
                        <input type="text" class="form-control" value="{{ $invoice->student->student_name_ar }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label>تفاصيل الفاتورة:</label>
                        <textarea name="invoice_details" class="form-control" rows="3" required>{{ old('invoice_details', $invoice->invoice_details) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label>المبلغ:</label>
                        <input type="number" name="amount" class="form-control" value="{{ old('amount', $invoice->amount) }}" required>
                    </div>

                    <div class="mb-3">
                        <label>تاريخ الاستحقاق:</label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label>مصدر الدفع:</label>
                        <select name="payment_sources_id" class="form-control">
                            <option value="">-- اختر --</option>
                            @foreach($sources as $source)
                                <option value="{{ $source->id }}" {{ $invoice->payment_sources_id == $source->id ? 'selected' : '' }}>
                                    {{ $source->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn"style="background-color: #196098;color: white;">تحديث الفاتورة</button>
                    <a href="{{ url()->previous() }}" class="btn "style="background-color: #e94c21;color: white;">رجوع</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
