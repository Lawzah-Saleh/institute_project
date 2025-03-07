<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePrice;
use Illuminate\Http\Request;

class CoursePriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coursePrices = CoursePrice::with('course')->get();
        return view('admin.pages.course-prices', compact('coursePrices'));
    }
    public function toggle($id)
    {
        $price = CoursePrice::findOrFail($id);
        $price->state = !$price->state; // Toggle the state (active/inactive)
        $price->save();

        return redirect()->route('course-prices.index')->with('success', 'تم تحديث حالة السعر بنجاح!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::all();
        return view('admin.pages.add-course-price', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'price' => 'required|numeric',
            'price_details' => 'nullable|numeric',
            'date' => 'required|date',
            'price_approval' => 'required|date',
            'state' => 'required|boolean',
        ]);

        CoursePrice::create($request->all());

        return redirect()->route('course-prices.index')->with('success', 'تم إضافة السعر بنجاح!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $price = CoursePrice::findOrFail($id);
        $courses = Course::all();
        return view('admin.pages.edit-course-price', compact('price', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'price' => 'required|numeric',
            'price_details' => 'nullable|numeric',
            'date' => 'required|date',
            'price_approval' => 'required|date',
            'state' => 'required|boolean',
        ]);

        $price = CoursePrice::findOrFail($id);
        $price->update($request->all());

        return redirect()->route('course-prices.index')->with('success', 'تم تعديل السعر بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $price = CoursePrice::findOrFail($id);
        $price->delete();

        return redirect()->route('course-prices.index')->with('success', 'تم حذف السعر بنجاح!');
    }
}
