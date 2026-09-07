<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $orderIndex = $course->modules()->max('order_index') + 1;

        $course->modules()->create([
            'title' => $request->title,
            'description' => $request->description,
            'order_index' => $orderIndex,
        ]);

        return back()->with('success', 'Module added successfully.');
    }

    public function update(Request $request, Course $course, Module $module)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $module->update($request->only('title', 'description'));

        return back()->with('success', 'Module updated successfully.');
    }

    public function destroy(Course $course, Module $module)
    {
        $module->delete();
        return back()->with('success', 'Module deleted successfully.');
    }
}
