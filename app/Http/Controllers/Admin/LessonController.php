<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function store(Request $request, Course $course, Module $module)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'duration_minutes' => 'nullable|integer',
        ]);

        $orderIndex = $module->lessons()->max('order_index') + 1;

        $module->lessons()->create([
            'title' => $request->title,
            'content' => $request->content,
            'video_url' => $request->video_url,
            'duration_minutes' => $request->duration_minutes,
            'order_index' => $orderIndex,
        ]);

        return back()->with('success', 'Lesson added successfully.');
    }

    public function update(Request $request, Course $course, Module $module, Lesson $lesson)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'duration_minutes' => 'nullable|integer',
        ]);

        $lesson->update($request->only('title', 'content', 'video_url', 'duration_minutes'));

        return back()->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Course $course, Module $module, Lesson $lesson)
    {
        $lesson->delete();
        return back()->with('success', 'Lesson deleted successfully.');
    }
}
