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
            'phones' => 'nullable|array',
            'phones.*' => 'nullable|string|max:20',
            'qualification' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . Auth::id(),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('profile.student.show')->with('error', 'Student profile not found.');
        }

        // ✅ Clean and encode phones
        $phones = array_filter($request->phones); // Remove blanks
        $phonesJson = json_encode(array_values($phones));

        // ✅ Handle image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile_images', 'public');
        } else {
            $imagePath = $student->image;
        }

        // ✅ Update student
        $student->update([
            'student_name_en' => $request->student_name_en,
            'phones' => $phonesJson, // ✅ Save here
            'qualification' => $request->qualification,
            'birth_date' => $request->birth_date,
            'birth_place' => $request->birth_place,
            'address' => $request->address,
            'email' => $request->email,
            'image' => $imagePath,
        ]);

        // ✅ Also update the email in the users table
        $user->email = $request->email;
        $user->save();

        return redirect()->route('profile.student.show')->with('success', 'تم تحديث البيانات بنجاح.');
    }


       // Update student's password

       public function updateStudentPassword(Request $request)
       {
           $request->validate([
               'old_password' => 'required',
               'new_password' => 'required|min:8|confirmed',
           ]);
       
           $user = Auth::user();
       
           if (!Hash::check($request->old_password, $user->password)) {
               return redirect()->back()->with('error', 'كلمة المرور الحالية غير صحيحة.');
           }
       
           $user->password = Hash::make($request->new_password);
           $user->save();
       
           return redirect()->route('profile.student.show')->with('success', 'تم تغيير كلمة المرور بنجاح.');
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
