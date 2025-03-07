<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Employee;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        // Filter by ID
        if ($request->filled('search_id')) {
            $query->where('id', $request->search_id);
        }

        // Filter by Name
        if ($request->filled('search_name')) {
            $query->where('name_en', 'like', '%' . $request->search_name . '%')
                  ->orWhere('name_ar', 'like', '%' . $request->search_name . '%');
        }

        // Filter by Job Type (e.g., Teacher)
        if ($request->filled('emptype')) {
            $query->where('emptype', $request->emptype);
        }

        $employees = $query->get();

        return view('admin.pages.employees.index', compact('employees'));
    }


    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        return view('admin.pages.employees.create');
    }

    /**
     * Store a newly created employee in the database.
     */


    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:300',
            'name_ar' => 'required|string|max:300',
            'phone' => 'required|numeric',
            'address' => 'required|string',
            'gender' => 'required|string',
            'Day_birth' => 'required|date',
            'place_birth' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'email' => 'required|email|unique:users,email',
            'emptype' => 'required|string',
            'state' => 'required|string',
            'role_id' => 'required|exists:roles,id', // Make sure role_id is passed
        ]);

        // Create User First
        $defaultPassword = 'password123'; // Change this as needed
        $user = User::create([
            'name' => $request->name_ar,
            'email' => $request->email,
            'password' => Hash::make($defaultPassword),
        ]);

        // Assign Role to User
        $role = Role::find($request->role_id);
        if ($role) {
            $user->assignRole($role->name); // This will update `model_has_roles`
        }

        // Prepare Employee Data
        $data = $request->all();
        $data['user_id'] = $user->id; // Link Employee to User

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('employees', 'public');
        }

        // Create Employee & Link to User
        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Employee and User created successfully');
    }


    /**
     * Show the form for editing an employee.
     */
    public function edit(Employee $employee)
    {
        return view('admin.pages.employees.edit', compact('employee'));
    }

    /**
     * Update an existing employee.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name_en' => 'required|string|max:300',
            'name_ar' => 'required|string|max:300',
            'phone' => 'required|numeric',
            'address' => 'required|string',
            'gender' => 'required|string',
            'Day_birth' => 'required|date',
            'place_birth' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'email' => 'nullable|email',
            'emptype' => 'required|string',
            'state' => 'required|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }
            $data['image'] = $request->file('image')->store('employees', 'public');
        }

        $employee->update($data);
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
    }

    /**
     * Delete an employee.
     */
    public function destroy(Employee $employee)
    {
        if ($employee->image) {
            Storage::disk('public')->delete($employee->image);
        }

        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
    }
}
