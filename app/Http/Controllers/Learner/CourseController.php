<?php
namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $enrollments = Enrollment::with('course.modules.lessons')
            ->where('user_id', $user->id)
            ->get();
            
        return Inertia::render('Learner/MyCourses', [
            'enrollments' => $enrollments
        ]);
    }

    public function show(Request $request, Course $course)
    {
        $user = $request->user();
        
        $enrollment = Enrollment::with(['course.modules.lessons'])
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        // Load progress for this user
        $progress = \App\Models\LessonProgress::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->get();

        return Inertia::render('Learner/Viewer', [
            'enrollment' => $enrollment,
            'progress' => $progress
        ]);
    }
}
