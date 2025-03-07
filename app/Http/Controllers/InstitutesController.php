<?php

namespace App\Http\Controllers;

use App\Models\Institutes;
use Illuminate\Http\Request;

class InstitutesController extends Controller
{
    // عرض معلومات المعهد
    public function index()
    {
        $institutes = institutes::all();
        return view('home', data: compact('institutes'));
    }

    // عرض نموذج إنشاء معهد جديد
    public function create()
    {
        // return view('institutes.create');
    }

    // حفظ معهد جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|image',
            'about_us' => 'nullable|string',
            'about_image' => 'nullable|image',
            'institute_servicies' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo_url')) {
            $data['logo_url'] = $request->file('logo_url')->store('institutes', 'public');
        }

        if ($request->hasFile('about_image')) {
            $data['about_image'] = $request->file('about_image')->store('institutes', 'public');
        }

        institutes::create($data);

        // return redirect()->route('institutes.index')->with('success', 'Institute created successfully.');
    }

    // عرض معهد محدد
    public function show(institutes $Institutes)
    {
        // return view('institutes.show', compact('institute'));
    }

    // عرض نموذج تعديل معهد
    public function edit(institutes $Institutes)
    {
        // return view('institutes.edit', compact('institute'));
    }

    // تحديث بيانات المعهد
    public function update(Request $request, Institutes $Institutes)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|image',
            'about_us' => 'nullable|string',
            'about_image' => 'nullable|image',
            'institute_servicies' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo_url')) {
            $data['logo_url'] = $request->file('logo_url')->store('institutes', 'public');
        }

        if ($request->hasFile('about_image')) {
            $data['about_image'] = $request->file('about_image')->store('institutes', 'public');
        }

        $Institutes->update($data);

        // return redirect()->route('institutes.index')->with('success', 'Institute updated successfully.');
    }

    // حذف معهد
    public function destroy(Institutes $Institutes)
    {
        $Institutes->delete();
        // return redirect()->route('institutes.index')->with('success', 'Institute deleted successfully.');
    }
}
