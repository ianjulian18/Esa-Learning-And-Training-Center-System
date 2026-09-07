<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PositionController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Positions/Index', [
            'positions' => Position::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:positions,code',
            'name' => 'required|string|max:255',
        ]);
        Position::create($validated);
        return redirect()->back()->with('success', 'Position created successfully.');
    }

    public function update(Request $request, Position $position)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:positions,code,' . $position->id,
            'name' => 'required|string|max:255',
        ]);
        $position->update($validated);
        return redirect()->back()->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->back()->with('success', 'Position deleted successfully.');
    }
}
