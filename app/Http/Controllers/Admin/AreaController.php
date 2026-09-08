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
            'Areas' => Area::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:Areas,code',
            'name' => 'required|string|max:255',
        ]);
        Area::create($validated);
        return redirect()->back()->with('success', 'Area created successfully.');
    }

    public function update(Request $request, Area $Area)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:Areas,code,' . $Area->id,
            'name' => 'required|string|max:255',
        ]);
        $Area->update($validated);
        return redirect()->back()->with('success', 'Area updated successfully.');
    }

    public function destroy(Area $Area)
    {
        $Area->delete();
        return redirect()->back()->with('success', 'Area deleted successfully.');
    }
}

