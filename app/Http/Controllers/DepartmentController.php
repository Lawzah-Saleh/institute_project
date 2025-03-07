<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Course;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::query(); // بدء استعلام فارغ

        // تحقق مما إذا كان هناك طلب للبحث
        if ($request->has('search') && $request->search != '') {
            $query->where('department_name', 'like', '%' . $request->search . '%');
        }

        $departments = $query->get(); // تنفيذ الاستعلام وجلب النتائج

        return view('admin.pages.departments', compact('departments'));
    }

    public function toggleState(Department $department)
    {
        // Toggle the state
        $department->state = !$department->state;
        $department->save();

        // Redirect back with a success message
        return redirect()->route('departments.index')->with('success', 'تم تحديث حالة القسم بنجاح!');
    }
    public function getCourses($departmentId)
    {
        $courses = Course::where('department_id', $departmentId)->get();
        return response()->json($courses);
    }




    public function create()
    {
        // Show the create department form
        return view('admin.pages.add-department');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'department_name' => 'required|string|max:255',
            'department_info' => 'nullable|string',
            'state' => 'required|boolean',
        ]);

        // Create a new department
        Department::create($request->all());

        // Redirect back with success message
        return redirect()->route('departments.index')->with('success', 'تم إضافة القسم بنجاح!');
    }

    public function edit(Department $department)
    {
        // Show the edit department form
        return view('admin.pages.edit-department', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        // Validate the request data
        $request->validate([
            'department_name' => 'required|string|max:255',
            'department_info' => 'nullable|string',
            'state' => 'required|boolean',
        ]);

        // Update the department
        $department->update($request->all());

        // Redirect back with success message
        return redirect()->route('departments.index')->with('success', 'تم تحديث القسم بنجاح!');
    }

    public function destroy(Department $department)
    {
        // Delete the department
        $department->delete();

        // Redirect back with success message
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully!');
    }
}
