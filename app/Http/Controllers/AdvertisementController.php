<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{

    public function index(Request $request)
    {
        $query = Advertisement::query();
    
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('title', 'LIKE', "%$search%")
                  ->orWhere('content', 'LIKE', "%$search%");
        }
    
        $advertisements = $query->orderBy('publish_date', 'desc')->paginate(10);
    
        return view('admin.pages.advertisements.index', compact('advertisements'));
    }
    

    /**
     * عرض نموذج إنشاء إعلان جديد
     */
    public function create()
    {
        return view('admin.pages.advertisements.create');
    }

 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'end_date' => 'required|date|after:today',
        ]);
    
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('advertisements', 'public');
        }
    
        $validated['publish_date'] = now();
    
        $validated['state'] = now()->lte($validated['end_date']);
    
        Advertisement::create($validated);
    
        return redirect()->route('advertisements.index')->with('success', 'تم إضافة الإعلان بنجاح!');
    }
    

    public function show(Advertisement $advertisement)
    {
        return view('admin.pages.advertisements.show', compact('advertisement'));
    }
    


    public function edit(Advertisement $advertisement)
    {
        return view('admin.pages.advertisements.edit', compact('advertisement'));
    }


    public function update(Request $request, Advertisement $advertisement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'end_date' => 'required|date|after:today',
        ]);
    
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('advertisements', 'public');
        }
    
        $validated['publish_date'] = $advertisement->publish_date ?? now();
    
        $validated['state'] = now()->lte($validated['end_date']);
    
        $advertisement->update($validated);
    
        return redirect()->route('advertisements.index')->with('success', 'تم تعديل الإعلان بنجاح!');
    }
    
    public function destroy(Advertisement $advertisement)
    {
        // حذف الصورة من التخزين
        if ($advertisement->image) {
            Storage::disk('public')->delete($advertisement->image);
        }

        $advertisement->delete();

        return redirect()->route('advertisements.index')->with('success', 'تم حذف الإعلان بنجاح.');
    }
}
