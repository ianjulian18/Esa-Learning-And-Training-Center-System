<?php
namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\Course;
use App\Models\Question;
use App\Models\Enrollment;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssessmentController extends Controller
{
    public function show(Request $request, Course $course, Assessment $assessment)
    {
        $user = $request->user();

        // Prevent multiple simultaneous attempts or retaking if passed
        $previousAttempt = AssessmentAttempt::where('user_id', $user->id)
            ->where('assessment_id', $assessment->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($previousAttempt && $previousAttempt->status === 'PASSED') {
            return redirect()->route('learner.courses.show', $course->id)->with('error', 'You have already passed this assessment.');
        }

        // Generate questions (randomly selected from all banks, simplified for now)
        $questions = Question::inRandomOrder()->limit($assessment->total_questions)->get();

        // Create new attempt
        $attempt = AssessmentAttempt::create([
            'assessment_id' => $assessment->id,
            'user_id' => $user->id,
            'status' => 'IN_PROGRESS',
            'started_at' => now()
        ]);

        return Inertia::render('Learner/Assessment', [
            'course' => $course,
            'assessment' => $assessment,
            'attempt' => $attempt,
            'questions' => $questions->map(function($q) {
                return [
                    'id' => $q->id,
                    'content' => $q->content,
                    'type' => $q->type,
                    'options' => $q->options
                ]; // Don't send correct answers to frontend
            })
        ]);
    }

    public function submit(Request $request, Course $course, AssessmentAttempt $attempt)
    {
        $validated = $request->validate([
            'answers' => 'required|array'
        ]);

        $correctCount = 0;
        $total = $attempt->assessment->total_questions;

        foreach ($validated['answers'] as $questionId => $answer) {
            $question = Question::find($questionId);
            $isCorrect = $question && $question->correct_answer === $answer;
            if ($isCorrect) $correctCount++;

            $attempt->answers()->create([
                'question_id' => $questionId,
                'user_answer' => $answer,
                'is_correct' => $isCorrect
            ]);
        }

        $score = round(($correctCount / $total) * 100);
        $passed = $score >= $attempt->assessment->passing_grade;

        $attempt->update([
            'score' => $score,
            'status' => $passed ? 'PASSED' : 'FAILED',
            'completed_at' => now()
        ]);

        if ($passed && $attempt->assessment->type === 'POST_TEST') {
            $enrollment = Enrollment::where('user_id', $attempt->user_id)
                ->where('course_id', $course->id)
                ->first();
                
            if ($enrollment) {
                $enrollment->update(['status' => 'COMPLETED', 'completion_date' => now(), 'score' => $score]);
                CertificateService::issueCertificate($enrollment);
            }
        }

        return redirect()->route('learner.courses.show', $course->id)->with('message', "Assessment submitted! Your score: $score. " . ($passed ? "You passed!" : "You failed."));
    }
}

