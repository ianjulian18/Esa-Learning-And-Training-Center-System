<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::withCount('modules')->orderBy('created_at', 'desc')->paginate(12);
        return Inertia::render('Admin/Courses/Index', [
            'courses' => $courses,
            'flash' => request()->session()->get('flash')
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Courses/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:courses',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'passing_grade' => 'required|integer|min:0|max:100',
            'status' => 'required|in:DRAFT,PUBLISHED,ARCHIVED',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        $data = $request->except('thumbnail');
        
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = Storage::url($request->file('thumbnail')->store('thumbnails', 'public'));
        }

        Course::create($data);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $course->load('modules.lessons');
        return Inertia::render('Admin/Courses/Show', [
            'course' => $course
        ]);
    }

    public function edit(Course $course)
    {
        return Inertia::render('Admin/Courses/Edit', [
            'course' => $course
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('courses')->ignore($course->id)],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'passing_grade' => 'required|integer|min:0|max:100',
            'status' => 'required|in:DRAFT,PUBLISHED,ARCHIVED',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        $data = $request->except('thumbnail');
        
        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $course->thumbnail));
            }
            $data['thumbnail'] = Storage::url($request->file('thumbnail')->store('thumbnails', 'public'));
        }

        $course->update($data);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        if ($course->thumbnail) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $course->thumbnail));
        }
        $course->delete();
        
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}
