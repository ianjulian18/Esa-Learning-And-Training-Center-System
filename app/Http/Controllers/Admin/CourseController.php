<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Principal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Courses/Index', [
            'courses' => Course::with('principals')->latest()->get()
        ]);
    }

        public function show(Course $course)
    {
        $course->load('modules.lessons');
        return Inertia::render('Admin/Courses/Curriculum', [
            'course' => $course
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Courses/Create', [
            'principals' => Principal::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:courses',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer',
            'passing_grade' => 'required|integer|min:0|max:100',
            'principal_ids' => 'required|array',
            'principal_ids.*' => 'exists:principals,id',
        ]);

        $course = Course::create([
            'code' => $validated['code'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'duration' => $validated['duration'],
            'passing_grade' => $validated['passing_grade'],
            'status' => 'ACTIVE'
        ]);

        $course->principals()->sync($validated['principal_ids']);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }
}

