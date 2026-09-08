<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssessmentController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Assessments/Index', [
            'assessments' => Assessment::with('course')->get()
        ]);
    }

    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'type' => 'required|in:PRE_TEST,POST_TEST',
            'total_questions' => 'required|integer|min:1',
            'passing_grade' => 'required|integer|min:1|max:100'
        ]);

        $course->assessments()->updateOrCreate(
            ['type' => $validated['type']],
            $validated
        );

        return back()->with('success', 'Assessment configuration saved.');
    }
}
