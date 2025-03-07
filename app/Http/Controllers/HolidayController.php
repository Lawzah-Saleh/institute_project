<?php
namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::orderBy('date', 'asc')->get();
        return view('admin.pages.holidays.index', compact('holidays'));
    }

    public function create()
    {
        return view('admin.pages.holidays.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:holidays,date',
            'name' => 'required|string|max:255',
        ]);

        Holiday::create($request->all());

        return redirect()->route('holidays.index')->with('success', 'Holiday added successfully.');
    }

    public function edit(Holiday $holiday)
    {
        return view('admin.pages.holidays.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'date' => 'required|date|unique:holidays,date,' . $holiday->id,
            'name' => 'required|string|max:255',
        ]);

        $holiday->update($request->all());

        return redirect()->route('holidays.index')->with('success', 'Holiday updated successfully.');
    }
    public function toggleState($id)
{
    $holiday = Holiday::findOrFail($id);
    $holiday->state = !$holiday->state; // يعكس الحالة (1 يصبح 0 والعكس)
    $holiday->save();

    return redirect()->route('holidays.index')->with('success', 'تم تحديث حالة الإجازة بنجاح.');
}


    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Holiday deleted successfully.');
    }
}
