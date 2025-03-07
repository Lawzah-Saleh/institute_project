<?php

namespace App\Http\Controllers;

use App\Models\advertisements;
use Illuminate\Http\Request;

class AdvertisementsController extends Controller
{
    // عرض جميع الإعلانات
    public function index()
    {
        $advertisements = advertisements::all();
        return view('home', compact('advertisements'));
    }

    // عرض نموذج إضافة إعلان جديد
    public function create()
    {
        // return view('advertisements.create');
    }

    // حفظ إعلان جديد
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required',
            'image' => 'required|image',
            'publish_date' => 'required|date',
            'end_date' => 'required|date',
            'state' => 'required|boolean',
        ]);

        $imagePath = $request->file('image')->store('advertisements', 'public');

        advertisements::create([
            'text' => $request->text,
            'image' => $imagePath,
            'publish_date' => $request->publish_date,
            'end_date' => $request->end_date,
            'state' => $request->state,
        ]);

        // return redirect()->route('advertisements.index')->with('success', 'Advertisement created successfully.');
    }

    // عرض إعلان محدد
    public function show(advertisements $advertisement)
    {
        // return view('advertisements.show', compact('advertisement'));
    }

    // عرض نموذج تعديل إعلان
    public function edit(advertisements $advertisement)
    {
        // return view('advertisements.edit', compact('advertisement'));
    }

    // تحديث إعلان
    public function update(Request $request, advertisements $advertisement)
    {
        $request->validate([
            'text' => 'required',
            'publish_date' => 'required|date',
            'end_date' => 'required|date',
            'state' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('advertisements', 'public');
            $advertisement->update(['image' => $imagePath]);
        }

        $advertisement->update($request->only('text', 'publish_date', 'end_date', 'state'));

        // return redirect()->route('advertisements.index')->with('success', 'Advertisement updated successfully.');
    }

    // حذف إعلان
    public function destroy(advertisements $advertisement)
    {
        $advertisement->delete();
        // return redirect()->route('advertisements.index')->with('success', 'Advertisement deleted successfully.');
    }
}
