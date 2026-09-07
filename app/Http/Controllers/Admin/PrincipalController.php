<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Principal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PrincipalController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Principals/Index', [
            'principals' => Principal::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:principals,code',
            'name' => 'required|string|max:255',
        ]);
        Principal::create($validated);
        return redirect()->back()->with('success', 'Principal created successfully.');
    }

    public function update(Request $request, Principal $principal)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:principals,code,' . $principal->id,
            'name' => 'required|string|max:255',
        ]);
        $principal->update($validated);
        return redirect()->back()->with('success', 'Principal updated successfully.');
    }

    public function destroy(Principal $principal)
    {
        $principal->delete();
        return redirect()->back()->with('success', 'Principal deleted successfully.');
    }
}
