<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            // Redirect to the login page or handle unauthenticated users
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return view('admin.pages.dashboard');
        } elseif ($user->hasRole('teacher')) {
            return view('Teacher-dashboard.dashboard');
        } elseif ($user->hasRole('student')) {
            return view('dashboard-Student.dashboard');
        }

        // If the user has no role, deny access
        abort(403, 'Unauthorized action.');
        
    }
}
