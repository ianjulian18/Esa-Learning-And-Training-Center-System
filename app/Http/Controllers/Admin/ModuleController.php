<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $maxOrder = $course->modules()->max('order') ?? 0;

        $course->modules()->create([
            'code' => Str::slug($course->code . '-' . $validated['title']) . '-' . uniqid(),
            'title' => $validated['title'],
            'order' => $maxOrder + 1
        ]);

        return redirect()->back()->with('success', 'Module added.');
    }

    public function update(Request $request, Course $course, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $module->update([
            'title' => $validated['title']
        ]);

        return redirect()->back()->with('success', 'Module updated.');
    }

    public function destroy(Course $course, Module $module)
    {
        $module->delete();
        return redirect()->back()->with('success', 'Module deleted.');
    }
}
