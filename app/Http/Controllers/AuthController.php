<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // عرض صفحة تسجيل الدخول
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // معالجة تسجيل الدخول
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // توجيه المستخدم حسب دوره
            if ($user->hasRole('admin')) {
                return redirect()->route('admin_dashboard');
            } elseif ($user->hasRole('teacher')) {
                return redirect()->route('teacher_dashboard');
            } else {
                return redirect()->route('student_dashboard');
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // تسجيل الخروج
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
