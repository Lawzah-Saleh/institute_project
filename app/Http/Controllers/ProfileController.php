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
        return view('dashboard-Student.profile', compact('student'));
    }
    public function updateStudentProfile(Request $request)
    {
        $request->validate([
            'student_name_en' => 'required|string|max:300',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phones' => 'nullable|json',
            'qualification' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email,' . Auth::user()->student->id,
        ]);

        // Fetch the logged-in student's profile
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('profile.student.show')->with('error', 'Student profile not found.');
        }

        // Allow updates for everything **except** `student_name_ar` and `gender`
        $student->update([
            'student_name_en' => $request->student_name_en,
            'image' => $request->file('image') ? $request->file('image')->store('profile_images', 'public') : $student->image,
            'phones' => $request->phones,
            'qualification' => $request->qualification,
            'birth_date' => $request->birth_date,
            'birth_place' => $request->birth_place,
            'address' => $request->address,
            'email' => $request->email,
        ]);

        return redirect()->route('profile.student.show')->with('success', 'Profile updated successfully.');
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
