<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    public function showStudentProfile()
    {
        $student = Auth::user()->student;  // Assuming one-to-one relationship with the User model
        if (!$student) {
            return redirect()->route('home')->with('error', 'Student not found');
        }
        return view('student.profile', compact('student'));
    }
    


       // Update student's personal details
       public function updateStudentProfile(Request $request)
       {
           $request->validate([
               'student_name_en' => 'required|string|max:255',
               'student_name_ar' => 'required|string|max:255',
               'email' => 'required|email|unique:students,email,' . Auth::id(),
               'phones' => 'nullable|json',
               'address' => 'required|string|max:255',
           ]);
   
           $student = Auth::user()->student;
           $student->update([
               'student_name_en' => $request->student_name_en,
               'student_name_ar' => $request->student_name_ar,
               'email' => $request->email,
               'phones' => $request->phones,
               'address' => $request->address,
           ]);
   
           return redirect()->route('profile.student.show')->with('success', 'تم تحديث البروفايل بنجاح');
       }
   
       // Update student's password
       public function updateStudentPassword(Request $request)
       {
           $request->validate([
               'old_password' => 'required|string|min:8',
               'new_password' => 'required|string|min:8|confirmed',
           ]);
   
           $user = Auth::user();
   
           // Check if the old password is correct
           if (!Hash::check($request->old_password, $user->password)) {
               return back()->withErrors(['old_password' => 'كلمة المرور القديمة غير صحيحة.']);
           }
   
           // Update the password
           $user->update([
               'password' => Hash::make($request->new_password),
           ]);
   
           return redirect()->route('profile.student.show')->with('success', 'تم تغيير كلمة المرور بنجاح');
       }
    // للمدرس
    public function showTeacherProfile()
    {
        $teacher = Auth::user()->teacher;
        return view('teacher.profile', compact('teacher'));
    }

    public function updateTeacherProfile(Request $request)
    {
        // نفس الطريقة كما في الطالب
    }

    public function updateTeacherPassword(Request $request)
    {
        // نفس الطريقة كما في الطالب
    }

    // للإداري
    public function showAdminProfile()
    {
        $admin = Auth::user()->admin;
        return view('admin.profile', compact('admin'));
    }

    public function updateAdminProfile(Request $request)
    {
        // نفس الطريقة كما في الطالب
    }

    public function updateAdminPassword(Request $request)
    {
        // نفس الطريقة كما في الطالب
    }
}
