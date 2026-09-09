<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AreaController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Areas/Index', [
            'Areas' => Area::with('region')->latest()->get(), 'regions' => \App\Models\Region::orderBy('name')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id', 'code' => 'required|string|max:50|unique:areas,code',
            'name' => 'required|string|max:255',
        ]);
        Area::create($validated);
        return redirect()->back()->with('success', 'Area created successfully.');
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id', 'code' => 'required|string|max:50|unique:areas,code,' . $area->id,
            'name' => 'required|string|max:255',
        ]);
        $area->update($validated);
        return redirect()->back()->with('success', 'Area updated successfully.');
    }

    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->back()->with('success', 'Area deleted successfully.');
    }
}




