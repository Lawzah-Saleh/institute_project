<?php

namespace App\Http\Controllers;

use App\Models\Institute;
use App\Models\Advertisement;
use App\Models\Course;
use App\Models\Department;

class HomeController extends Controller
{
    public function index()
    {
        // جلب البيانات من جميع الجداول
        $institute = Institute::first(); // جلب بيانات المعهد
        $advertisements = Advertisement::where('state', 1)->get(); // الإعلانات النشطة
        $courses = Course::where('state', 1)->get(); // الكورسات النشطة
        $departments = Department::where('state', 1)->get(); // الأقسام النشطة

        // تمرير البيانات إلى القالب
        return view('home', compact('institute', 'advertisements', 'courses', 'departments'));
    }
}
