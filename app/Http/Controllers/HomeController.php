<?php

namespace App\Http\Controllers;

use App\Models\institutes;
use App\Models\advertisements;
use App\Models\Course;
use App\Models\Department;

class HomeController extends Controller
{
    public function index()
    {
        // جلب البيانات من جميع الجداول
        $institute = institutes::first(); // جلب بيانات المعهد
        $advertisements = advertisements::where('state', 1)->get(); // الإعلانات النشطة
        $courses = Course::where('state', 1)->get(); // الكورسات النشطة
        $departments = Department::where('state', 1)->get(); // الأقسام النشطة

        // تمرير البيانات إلى القالب
        return view('home', compact('institute', 'advertisements', 'courses', 'departments'));
    }
}
