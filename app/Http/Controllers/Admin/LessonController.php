<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function store(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'material_type' => 'required|in:YOUTUBE,VIDEO_UPLOAD,DOCUMENT_UPLOAD,TEXT',
            'video_url' => 'nullable|url',
            'file' => 'nullable|file|mimes:mp4,pdf|max:102400', // 100MB max
            'duration_minutes' => 'nullable|integer',
            'content' => 'nullable|string'
        ]);

        $maxOrder = $module->lessons()->max('order') ?? 0;

        $lesson = $module->lessons()->create([
            'code' => Str::slug($module->code . '-' . $validated['title']) . '-' . uniqid(),
            'title' => $validated['title'],
            'order' => $maxOrder + 1
        ]);

        $this->saveMaterial($lesson, $request, $validated);

        return redirect()->back()->with('success', 'Lesson added.');
    }

    public function update(Request $request, Module $module, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'material_type' => 'required|in:YOUTUBE,VIDEO_UPLOAD,DOCUMENT_UPLOAD,TEXT',
            'video_url' => 'nullable|url',
            'file' => 'nullable|file|mimes:mp4,pdf|max:102400',
            'duration_minutes' => 'nullable|integer',
            'content' => 'nullable|string'
        ]);

        $lesson->update([
            'title' => $validated['title']
        ]);

        // Recreate materials to simplify update process
        $lesson->materials()->delete();
        $this->saveMaterial($lesson, $request, $validated);

        return redirect()->back()->with('success', 'Lesson updated.');
    }

    private function saveMaterial(Lesson $lesson, Request $request, array $validated)
    {
        if ($validated['material_type'] === 'YOUTUBE' && !empty($validated['video_url'])) {
            $lesson->materials()->create([
                'type' => 'VIDEO',
                'source_url' => $validated['video_url'],
                'duration' => $validated['duration_minutes'] ? $validated['duration_minutes'] * 60 : 0
            ]);
        } elseif (in_array($validated['material_type'], ['VIDEO_UPLOAD', 'DOCUMENT_UPLOAD']) && $request->hasFile('file')) {
            $path = $request->file('file')->store('lessons', 'public');
            $type = $validated['material_type'] === 'VIDEO_UPLOAD' ? 'VIDEO_UPLOAD' : 'DOCUMENT_UPLOAD';
            
            $lesson->materials()->create([
                'type' => $type,
                'source_url' => '/storage/' . $path,
                'duration' => $validated['duration_minutes'] ? $validated['duration_minutes'] * 60 : 0
            ]);
        } elseif ($validated['material_type'] === 'TEXT' && !empty($validated['content'])) {
            $lesson->materials()->create([
                'type' => 'TEXT',
                'source_url' => $validated['content'],
                'duration' => 0
            ]);
        }
    }

    public function destroy(Module $module, Lesson $lesson)
    {
        // Delete associated files if any
        foreach ($lesson->materials as $material) {
            if (in_array($material->type, ['VIDEO_UPLOAD', 'DOCUMENT_UPLOAD'])) {
                $relativePath = str_replace('/storage/', '', $material->source_url);
                Storage::disk('public')->delete($relativePath);
            }
        }
        
        $lesson->delete();
        return redirect()->back()->with('success', 'Lesson deleted.');
    }
}
