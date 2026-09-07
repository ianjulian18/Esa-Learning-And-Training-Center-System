<?php
namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Models\LessonProgress;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\Course;
use App\Services\CertificateService;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'lesson_id' => 'required|exists:lessons,id',
            'last_watched_position' => 'required|integer',
            'is_completed' => 'required|boolean'
        ]);

        $user = $request->user();

        // Ensure user is enrolled
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $validated['course_id'])
            ->firstOrFail();

        if ($enrollment->status === 'NOT_STARTED') {
            $enrollment->update(['status' => 'IN_PROGRESS']);
        }

        $progress = LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $validated['course_id'],
                'lesson_id' => $validated['lesson_id']
            ],
            [
                'last_watched_position' => $validated['last_watched_position'],
                'is_completed' => $validated['is_completed']
            ]
        );

        // Check if all lessons are completed
        $course = Course::with('modules.lessons', 'assessments')->find($validated['course_id']);
        
        $totalLessons = 0;
        foreach ($course->modules as $mod) {
            $totalLessons += $mod->lessons->count();
        }
        
        $completedLessons = LessonProgress::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('is_completed', true)
            ->count();
            
        $hasPostTest = $course->assessments()->where('type', 'POST_TEST')->exists();
        
        if ($completedLessons >= $totalLessons && !$hasPostTest) {
            if ($enrollment->status !== 'COMPLETED') {
                $enrollment->update(['status' => 'COMPLETED', 'completion_date' => now(), 'score' => 100]);
                CertificateService::issueCertificate($enrollment);
            }
        }

        return response()->json(['success' => true, 'progress' => $progress]);
    }
}

