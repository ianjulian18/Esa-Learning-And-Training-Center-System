<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegionController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Regions/Index', [
            'Regions' => Region::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:regions,code',
            'name' => 'required|string|max:255',
        ]);
        Region::create($validated);
        return redirect()->back()->with('success', 'Region created successfully.');
    }

    public function update(Request $request, Region $region)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:regions,code,' . $region->id,
            'name' => 'required|string|max:255',
        ]);
        $region->update($validated);
        return redirect()->back()->with('success', 'Region updated successfully.');
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return redirect()->back()->with('success', 'Region deleted successfully.');
    }
}


