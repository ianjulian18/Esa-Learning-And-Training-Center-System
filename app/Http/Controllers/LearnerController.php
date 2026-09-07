<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\AssessmentAnswer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LearnerController extends Controller
{
    public function myCourses()
    {
        $user = Auth::user();
        
        // My Enrolled Courses
        $enrollments = Enrollment::with('course')->where('user_id', $user->id)->get();
        
        $enrolledCourseIds = $enrollments->pluck('course_id')->toArray();
        
        // Available Courses to Enroll (Mocking Assignment Rule for now)
        $availableCourses = Course::where('status', 'PUBLISHED')
            ->whereNotIn('id', $enrolledCourseIds)
            ->withCount('modules')
            ->get();
            
        return Inertia::render('Learner/MyCourses', [
            'enrollments' => $enrollments,
            'availableCourses' => $availableCourses
        ]);
    }

    public function enroll(Request $request, Course $course)
    {
        $user = Auth::user();
        
        // Check if already enrolled
        if(Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->exists()){
            return back()->with('error', 'You are already enrolled in this course.');
        }

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'IN PROGRESS',
            'progress_percentage' => 0
        ]);

        return redirect()->route('learner.learn', $course->id)->with('success', 'Successfully enrolled in course!');
    }

    public function learn(Course $course)
    {
        $user = Auth::user();
        $enrollment = Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->firstOrFail();
        
        // Eager load modules and lessons
        $course->load(['modules.lessons' => function($query) {
            $query->orderBy('order_index');
        }]);

        // Get progress for each lesson
        $lessonProgress = LessonProgress::where('user_id', $user->id)->whereIn('lesson_id', $course->lessons()->pluck('id'))->get();
        $progressMap = $lessonProgress->keyBy('lesson_id');

        return Inertia::render('Learner/Viewer', [
            'course' => $course,
            'enrollment' => $enrollment,
            'progressMap' => $progressMap
        ]);
    }

    public function updateProgress(Request $request, Course $course)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'status' => 'required|string', // IN PROGRESS, COMPLETED
        ]);

        $user = Auth::user();
        $enrollment = Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->firstOrFail();

        $progress = LessonProgress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $request->lesson_id],
            [
                'status' => $request->status,
                'completed_at' => $request->status === 'COMPLETED' ? now() : null,
                'last_watched_position' => $request->input('last_watched_position', 0)
            ]
        );

        // Update total course progress percentage
        $totalLessons = $course->lessons()->count();
        if ($totalLessons > 0) {
            $completedLessons = LessonProgress::where('user_id', $user->id)
                ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                ->where('status', 'COMPLETED')
                ->count();
                
            $enrollment->progress_percentage = round(($completedLessons / $totalLessons) * 100);
            if ($enrollment->progress_percentage == 100) {
                // If there's no assessment, mark as COMPLETED
                if (!$course->assessments()->exists()) {
                    $enrollment->status = 'COMPLETED';
                    $enrollment->completed_at = now();
                }
            }
            $enrollment->save();
        }

        return response()->json(['success' => true, 'progress' => $progress, 'enrollment' => $enrollment]);
    }

    public function assessment(Course $course)
    {
        $user = Auth::user();
        $enrollment = Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->firstOrFail();
        
        $assessment = $course->assessments()->with(['questions.questionBank'])->firstOrFail();
        
        // Get previous attempts
        $attempts = AssessmentAttempt::where('user_id', $user->id)->where('assessment_id', $assessment->id)->get();

        return Inertia::render('Learner/Assessment', [
            'course' => $course,
            'assessment' => $assessment,
            'attempts' => $attempts,
            'enrollment' => $enrollment
        ]);
    }

    public function submitAssessment(Request $request, Course $course)
    {
        $user = Auth::user();
        $assessment = $course->assessments()->with('questions.questionBank')->firstOrFail();
        $enrollment = Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->firstOrFail();

        $answers = $request->input('answers', []); // Format: { question_id: answer_text }

        // Create attempt
        $attempt = AssessmentAttempt::create([
            'user_id' => $user->id,
            'assessment_id' => $assessment->id,
            'started_at' => now(), // Should ideally be set when they open it, but simplifying for now
            'finished_at' => now(),
            'score' => 0,
            'status' => 'FAILED'
        ]);

        $correctCount = 0;
        $totalQuestions = $assessment->questions->count();
        $essayQuestionsExist = false;

        foreach ($assessment->questions as $aq) {
            $qb = $aq->questionBank;
            $userAnswer = $answers[$aq->id] ?? null;

            $isCorrect = false;
            if ($qb->question_type === 'MULTIPLE_CHOICE' || $qb->question_type === 'TRUE_FALSE') {
                $isCorrect = ($qb->correct_answer === $userAnswer);
                if ($isCorrect) {
                    $correctCount++;
                }
            } else if ($qb->question_type === 'ESSAY') {
                $essayQuestionsExist = true;
                // For essay, requires manual grading later, default to not correct for auto-grading
            }

            AssessmentAnswer::create([
                'assessment_attempt_id' => $attempt->id,
                'assessment_question_id' => $aq->id,
                'answer_text' => $userAnswer,
                'is_correct' => $isCorrect,
                'score' => $isCorrect ? 100 : 0, // Simplified scoring per question
            ]);
        }

        $finalScore = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
        $passed = $finalScore >= $assessment->passing_grade;

        $attempt->update([
            'score' => $finalScore,
            'status' => $essayQuestionsExist ? 'PENDING_GRADING' : ($passed ? 'PASSED' : 'FAILED')
        ]);

        // Update enrollment if passed
        if ($passed && !$essayQuestionsExist) {
            $enrollment->update([
                'status' => 'COMPLETED',
                'completed_at' => now()
            ]);
        }

        return redirect()->route('learner.assessment', $course->id)->with('success', 'Assessment submitted successfully! Score: ' . $finalScore);
    }
}
