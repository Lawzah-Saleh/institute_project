@extends('layouts.app')

@section('content')
<div class="container">
    <h2>تم التسجيل بنجاح!</h2>
    <p>شكراً لتسجيلك. رقم الحافظة الخاص بك هو:</p>
    <h3>{{ session('success') }}</h3>
    <p>استخدم هذا الرقم عند الدفع عبر البنك.</p>
</div>
@endsection
