<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LearnerController extends Controller
{
    public function myCourses()
    {
        // For UI preview, fetch all published courses
        $courses = Course::where('status', 'PUBLISHED')->withCount('modules')->get();
        return Inertia::render('Learner/MyCourses', [
            'courses' => $courses
        ]);
    }

    public function learn($id)
    {
        $course = Course::with(['modules.lessons'])->findOrFail($id);
        return Inertia::render('Learner/Viewer', [
            'course' => $course
        ]);
    }

    public function assessment($id)
    {
        // Mock assessment for UI preview
        $course = Course::findOrFail($id);
        return Inertia::render('Learner/Assessment', [
            'course' => $course
        ]);
    }
}
