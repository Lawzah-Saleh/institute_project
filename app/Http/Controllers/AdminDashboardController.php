<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Course;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $studentsCount = Student::count();
        $teachersCount = Employee::where('emptype', 'teacher')->count();
        $departmentsCount = Department::count();
        $coursesCount = Course::count();

        return view('admin.pages.dashboard', compact('studentsCount', 'teachersCount', 'departmentsCount', 'coursesCount'));
    }

}
