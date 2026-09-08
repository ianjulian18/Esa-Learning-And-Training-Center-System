<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EntityController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Entitys/Index', [
            'Entitys' => Entity::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:Entitys,code',
            'name' => 'required|string|max:255',
        ]);
        Entity::create($validated);
        return redirect()->back()->with('success', 'Entity created successfully.');
    }

    public function update(Request $request, Entity $Entity)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:Entitys,code,' . $Entity->id,
            'name' => 'required|string|max:255',
        ]);
        $Entity->update($validated);
        return redirect()->back()->with('success', 'Entity updated successfully.');
    }

    public function destroy(Entity $Entity)
    {
        $Entity->delete();
        return redirect()->back()->with('success', 'Entity deleted successfully.');
    }
}

