<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Employee;
use App\Models\Qualification;
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

    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return view('admin.pages.employees.show', compact('employee'));
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
             'phones' => 'required|string',
             'address' => 'required|string',
             'gender' => 'required|in:male,female',
             'birth_date' => 'required|date',
             'birth_place' => 'required|string',
             'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
             'email' => 'required|email|unique:users,email',
             'emptype' => 'required|string',
             'state' => 'required|boolean',
             'role_id' => 'required|exists:roles,id', // Employee role
             'qualification_name' => 'required|array',
             'qualification_name.*' => 'required|string|max:255',
             'issuing_authority' => 'required|array',
             'issuing_authority.*' => 'required|string|max:255',
             'certification' => 'nullable|array',
             'certification.*' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
             'obtained_date' => 'nullable|array',
             'obtained_date.*' => 'nullable|date',
         ]);
     
         // Create User First
         $defaultPassword = 'password123'; // Set default password
         $user = User::create([
             'name' => $request->name_en,
             'email' => $request->email,
             'password' => Hash::make($defaultPassword),
         ]);
     
         // Assign Role to User
         $role = Role::find($request->role_id);
         if ($role) {
             $user->assignRole($role->name); // Assign employee role
         }
     
         // Prepare Employee Data
         $data = $request->all();
         $data['user_id'] = $user->id; // Link Employee to User
     
         if ($request->hasFile('image')) {
             $data['image'] = $request->file('image')->store('employees', 'public');
         }
     
         // Create Employee & Link to User
         $employee = Employee::create($data);
     
         // Save Qualifications
         foreach ($request->qualification_name as $index => $qualification) {
             $certificationPath = null;
             if ($request->hasFile("certification.$index")) {
                 $certificationPath = $request->file("certification.$index")->store('qualifications', 'public');
             }
     
             Qualification::create([
                 'employee_id' => $employee->id,
                 'qualification_name' => $qualification,
                 'issuing_authority' => $request->issuing_authority[$index],
                 'certification' => $certificationPath,
                 'obtained_date' => $request->obtained_date[$index] ?? null,
             ]);
         }
     
         return redirect()->route('employees.index')->with('success', 'Employee, User, and Qualifications created successfully');
     }
     public function toggleStatus($id)
{
    $employee = Employee::findOrFail($id);
    
    // Toggle activation status
    $employee->state = !$employee->state;
    $employee->save();

    return redirect()->back()->with('success', 'تم تحديث حالة الموظف بنجاح.');
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
            'phones' => 'required|string',
            'address' => 'required|string',
            'gender' => 'required|string',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'email' => 'nullable|email',
            'emptype' => 'required|string',
            'state' => 'required|boolean',
        ]);

        // Update Employee Data
        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }
            $data['image'] = $request->file('image')->store('employees', 'public');
        }

        $employee->update($data);

        // Update Qualifications
        if ($request->has('qualification_name')) {
            Qualification::where('employee_id', $employee->id)->delete();
            foreach ($request->qualification_name as $index => $name) {
                Qualification::create([
                    'employee_id' => $employee->id,
                    'qualification_name' => $name,
                    'issuing_authority' => $request->issuing_authority[$index],
                    'obtained_date' => $request->obtained_date[$index] ?? null,
                    'certification' => $request->hasFile("certification.$index") ? 
                        $request->file("certification.$index")->store('qualifications', 'public') : null
                ]);
            }
        }

        return redirect()->route('employees.index')->with('success', 'تم تحديث بيانات الموظف بنجاح');
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
