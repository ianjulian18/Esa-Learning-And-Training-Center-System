<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function store(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => 'required|in:VIDEO,DOCUMENT,TEXT',
            'content_url' => 'nullable|string',
            'duration' => 'nullable|integer',
            'order' => 'required|integer'
        ]);

        $module->lessons()->create($validated);

        return redirect()->back()->with('success', 'Lesson added.');
    }

    public function destroy(Module $module, Lesson $lesson)
    {
        $lesson->delete();
        return redirect()->back()->with('success', 'Lesson deleted.');
    }
}
