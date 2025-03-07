<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;
use App\Models\Course;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\CourseStudent;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function create()
    {
        $departments = Department::with('courses')->get();
        return view('admin.pages.students.register', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name_en' => 'required|string|max:255',
            'full_name_ar' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'gender' => 'required',
            'qualification' => 'required',
            'dob' => 'required|date',
            'birth_place' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg',
            'department_id' => 'required|exists:departments,id',
            'course_id' => 'required|exists:courses,id',
            'study_time' => 'required|in:8-10,10-12,2-4,4-6',
            'amount_paid' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($request) {
            $student = Student::create($request->except(['image', 'course_id', 'study_time', 'amount_paid']));

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('students', 'public');
                $student->update(['image' => $path]);
            }

            $courseStudent = CourseStudent::create([
                'student_id' => $student->id,
                'course_id' => $request->course_id,
                'study_time' => $request->study_time,
            ]);

            $invoice = Invoice::create([
                'student_id' => $student->id,
                'amount' => Course::find($request->course_id)->price,
                'reference' => uniqid('INV-'),
            ]);

            if ($request->amount_paid > 0) {
                Payment::create([
                    'student_id' => $student->id,
                    'invoice_id' => $invoice->id,
                    'amount' => $request->amount_paid,
                ]);
            }
        });

        return redirect()->route('students.create')->with('success', 'Student registered successfully!');
    }
}
