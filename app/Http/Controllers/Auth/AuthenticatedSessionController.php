<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
  // عملية التحقق من بيانات تسجيل الدخول
  $request->validate([
    'email' => ['required', 'email'],
    'password' => ['required'],
]);

// التحقق من صحة البيانات وتسجيل الدخول
if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
    $request->session()->regenerate();

    // توجيه المستخدم بناءً على دوره
    if (Auth::user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::user()->hasRole('teacher')) {
        return redirect()->route('teacher.dashboard');
    } elseif (Auth::user()->hasRole('student')) {
        return redirect()->route('student.dashboard');
    }
    

    // توجيه افتراضي إذا لم يكن لديه أي دور
    return redirect()->route('home');
    }   

    // إذا فشل تسجيل الدخول
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
