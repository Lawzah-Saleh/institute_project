<?php

namespace App\Http\Controllers;

use App\Models\StudentNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StudentNotificationsController extends Controller
{    
    public function index()
    {
        $student = Auth::user()->student;
    
        // Get notifications
        $notifications = DB::table('student_notifications')
            ->where('student_id', $student->id)
            ->orderByDesc('date')
            ->get();
    
        // ✅ Mark all unread notifications as read
        DB::table('student_notifications')
            ->where('student_id', $student->id)
            ->where('state', 'unread')
            ->update(['state' => 'read']);
    
        return view('dashboard-Student.notifications', compact('notifications', 'student'));
    }
    


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }


}
