<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer'
        ]);

        $course->modules()->create([
            'title' => $validated['title'],
            'order' => $validated['order']
        ]);

        return redirect()->back()->with('success', 'Module added.');
    }

    public function destroy(Course $course, Module $module)
    {
        $module->delete();
        return redirect()->back()->with('success', 'Module deleted.');
    }
}
