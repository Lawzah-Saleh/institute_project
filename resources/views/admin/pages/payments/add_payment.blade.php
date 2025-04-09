@extends('admin.layouts.app')
@section('content')
<div class="page-wrapper" style="background-color: #F9F9FB;">
    <div class="content container-fluid">

    <h2 class="mb-4"> إدارة رسوم الطلاب</h2>

    {{-- البحث عن الطالب --}}
    <div class="form-group mb-4">
        <label for="student_search">🔍 البحث عن الطالب (الاسم أو الإيميل)</label>
        <input type="text" id="student_search" class="form-control" placeholder="اكتب جزء من اسم الطالب أو بريده الإلكتروني...">
        <div id="student_results" class="list-group mt-2"></div>
    </div>

    {{-- بيانات الطالب المالية --}}
    <div id="student_info" class="card d-none p-3 mb-4">
        <h5>📄 معلومات الطالب المالية:</h5>
        <p><strong>الاسم:</strong> <span id="student_name"></span></p>
        <p><strong>المبلغ الكلي:</strong> <span id="paid_amount"></span> ريال</p>
        <p><strong>المدفوع:</strong> <span id="total_due"></span> ريال</p>
        <p><strong>المتبقي:</strong> <span id="remaining_amount" class="text-danger"></span> ريال</p>
    </div>

    {{-- نموذج إضافة الدفع --}}
    <form id="payment_form" action="{{ route('payments.store') }}" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="student_id" id="student_id">

        <div class="form-group">
            <label for="payment_amount"> المبلغ المدفوع</label>
            <input type="number" name="payment_amount" id="payment_amount" class="form-control" min="0.01" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="payment_sources_id"> مصدر الدفع</label>
            <select name="payment_sources_id" id="payment_sources_id" class="form-control" required>
                <option value="">-- اختر مصدر الدفع --</option>
                @foreach($paymentSources as $source)
                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="payment_date"> تاريخ الدفع</label>
            <input type="date" name="payment_date" id="payment_date" class="form-control" required>
        </div>

        <button type="submit" class="btn mt-3"style="background-color: #196098;color: white;"> إضافة الدفع</button>
    </form>
</div>

{{-- سكربت جافاسكربت + AJAX --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('student_search');
    const resultBox = document.getElementById('student_results');
    const form = document.getElementById('payment_form');
    const studentInfo = document.getElementById('student_info');

    searchInput.addEventListener('input', function () {
        const keyword = this.value;
        if (keyword.length < 2) {
            resultBox.innerHTML = '';
            return;
        }

        fetch(`/api/students/search?query=${keyword}`)
            .then(res => res.json())
            .then(data => {
                resultBox.innerHTML = '';
                data.forEach(student => {
                    const item = document.createElement('button');
                    item.classList.add('list-group-item', 'list-group-item-action');
                    item.textContent = `${student.name} (${student.email})`;
                    item.onclick = () => selectStudent(student);
                    resultBox.appendChild(item);
                });
            });
    });

    function selectStudent(student) {
    document.getElementById('student_search').value = student.name;

    // تعبئة البيانات
    document.getElementById('student_id').value = student.id;
    document.getElementById('student_name').textContent = student.name;
    document.getElementById('total_due').textContent = parseFloat(student.total_due).toFixed(2);
    document.getElementById('paid_amount').textContent = parseFloat(student.paid_amount).toFixed(2);
    
    const remaining = parseFloat(student.paid_amount) - parseFloat(student.total_due) ;
    document.getElementById('remaining_amount').textContent = remaining.toFixed(2);

    // إظهار المعلومات والنموذج
    document.getElementById('student_info').classList.remove('d-none');
    document.getElementById('payment_form').classList.remove('d-none');
    alert("البيانات جاهزة وتم عرضها ✅");

}
document.getElementById('payment_amount').addEventListener('input', function () {
    const entered = parseFloat(this.value);
    const remaining = parseFloat(document.getElementById('remaining_amount').textContent);
    if (entered > remaining) {
        alert('⚠️ لا يمكنك إدخال مبلغ أكبر من المبلغ المتبقي.');
        this.value = remaining.toFixed(2);
    }
});

});
</script>
@endsection
