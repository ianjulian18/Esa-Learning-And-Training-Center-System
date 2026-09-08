<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function store(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'nullable|url',
            'duration_minutes' => 'nullable|integer',
            'content' => 'nullable|string'
        ]);

        $maxOrder = $module->lessons()->max('order') ?? 0;

        $lesson = $module->lessons()->create([
            'code' => Str::slug($module->code . '-' . $validated['title']) . '-' . uniqid(),
            'title' => $validated['title'],
            'order' => $maxOrder + 1
        ]);

        if (!empty($validated['video_url'])) {
            $lesson->materials()->create([
                'type' => 'VIDEO',
                'source_url' => $validated['video_url'],
                'duration' => $validated['duration_minutes'] ? $validated['duration_minutes'] * 60 : 0
            ]);
        }

        if (!empty($validated['content'])) {
            $lesson->materials()->create([
                'type' => 'TEXT',
                'source_url' => $validated['content'],
                'duration' => 0
            ]);
        }

        return redirect()->back()->with('success', 'Lesson added.');
    }

    public function update(Request $request, Module $module, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $lesson->update([
            'title' => $validated['title']
        ]);

        return redirect()->back()->with('success', 'Lesson updated.');
    }

    public function destroy(Module $module, Lesson $lesson)
    {
        $lesson->delete();
        return redirect()->back()->with('success', 'Lesson deleted.');
    }
}
