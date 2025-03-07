<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee; // Use the Employee model

class TeacherController extends Controller
{
    /**
     * Display a list of teachers.
     */
    public function index()
    {
        // Get only employees who are teachers
        $teachers = Employee::where('emptype', 'teacher')->get();

        return view('admin.pages.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        return view('admin.pages.teachers.create');
    }

    /**
     * Store a newly created teacher in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female',
            'qualification' => 'required|string|max:255',
            'Day_birth' => 'required|date',
            'place_birth' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle Image Upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('teachers', 'public');
        }

        // Create Teacher Record
        Employee::create([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'qualification' => $request->qualification,
            'Day_birth' => $request->Day_birth,
            'place_birth' => $request->place_birth,
            'image' => $imagePath,
            'emptype' => 'teacher', // Set Employee Type as Teacher
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher added successfully!');
    }

    /**
     * Show the details of a teacher.
     */
    public function show($id)
    {
        $teacher = Employee::findOrFail($id);
        return view('admin.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing a teacher.
     */
    public function edit($id)
    {
        $teacher = Employee::findOrFail($id);
        return view('admin.pages.teachers.edit', compact('teacher'));
    }

    /**
     * Update the teacher information.
     */
    public function update(Request $request, $id)
    {
        $teacher = Employee::findOrFail($id);

        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female',
            'qualification' => 'required|string|max:255',
            'Day_birth' => 'required|date',
            'place_birth' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($teacher->image) {
                Storage::disk('public')->delete($teacher->image);
            }

            $teacher->image = $request->file('image')->store('teachers', 'public');
        }

        // Update Teacher Information
        $teacher->update([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'qualification' => $request->qualification,
            'Day_birth' => $request->Day_birth,
            'place_birth' => $request->place_birth,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully!');
    }

    /**
     * Delete a teacher from the database.
     */
    public function destroy($id)
    {
        $teacher = Employee::findOrFail($id);
        if ($teacher->image) {
            Storage::disk('public')->delete($teacher->image);
        }

        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully!');
    }
}
