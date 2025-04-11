<?php

// app/Http/Middleware/IsTeacher.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsTeacher
{
    public function handle($request, Closure $next)
    {
        // إذا لم يكن المستخدم مسجل الدخول أو لا يمتلك علاقة مع الموظف
        if (!Auth::check() || !Auth::user()->employee || Auth::user()->employee->emptype !== 'teacher') {
            return redirect('/login')->with('error', 'غير مسموح لك بالوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
}



