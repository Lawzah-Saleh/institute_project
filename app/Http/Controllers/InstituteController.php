<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institute;
use Illuminate\Support\Facades\Storage;

class InstituteController extends Controller
{

    public function index()
    {
        $institute = Institute::first(); // جلب أول معهد (لأن هناك واحد فقط)
        
        return view('admin.pages.institute.index', compact('institute'));
    }
    

 
    public function create()
    {
        return view('admin.pages.institute.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'institute_name' => 'required|string|max:255',
            'email' => 'required|email|unique:institutes,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'institute_description' => 'nullable|string',
            'about_us' => 'nullable|string',
            'about_image' => 'nullable|image|max:2048',
        ]);
    
        if ($request->hasFile('about_image')) {
            $validated['about_image'] = $request->file('about_image')->store('institutes', 'public');
        }
    
        Institute::create($validated);
    
        return redirect()->route('institute.index')->with('success', 'تمت إضافة المعهد بنجاح.');
    }
    

 
    public function show($id)
    {
        $institute = Institute::findOrFail($id);
        return view('admin.pages.institute.show', compact('institute'));
    }

    /**
     * عرض نموذج تعديل معهد.
     */
    public function edit($id)
    {
        $institute = Institute::findOrFail($id);
        return view('admin.pages.institute.edit', compact('institute'));
    }

 
    public function update(Request $request, $id)
    {
        $institute = Institute::findOrFail($id);

        $validated = $request->validate([
            'institute_name' => 'required|string|max:255',
            'email' => 'required|email|unique:institutes,email,' . $id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'institute_description' => 'nullable|string',
            'about_us' => 'nullable|string',
            'about_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('about_image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($institute->about_image) {
                Storage::disk('public')->delete($institute->about_image);
            }
        
            // رفع الصورة الجديدة إلى التخزين العام
            $validated['about_image'] = $request->file('about_image')->store('institutes', 'public');
        }
        

        $institute->update($validated);

        return redirect()->route('institute.index')->with('success', 'تم تحديث بيانات المعهد بنجاح!');
    }


    public function destroy($id)
    {
        $institute = Institute::findOrFail($id);

        // حذف الصورة إذا كانت موجودة
        if ($institute->about_image) {
            Storage::delete($institute->about_image);
        }

        $institute->delete();

        return redirect()->route('institute.index')->with('success', 'تم حذف المعهد بنجاح!');
    }
}
