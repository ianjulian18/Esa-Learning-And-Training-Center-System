<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\AssignmentRuleController;
use App\Http\Controllers\Admin\QuestionBankController;
use App\Http\Controllers\Admin\AssessmentController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Learner\CourseController as LearnerCourseController;
use App\Http\Controllers\Learner\ProgressController;
use App\Http\Controllers\Learner\AssessmentController as LearnerAssessmentController;
use App\Http\Controllers\Learner\CertificateController as LearnerCertificateController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\PrincipalController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\DepartmentController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Redirection based on roles
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasAnyRole(['super_admin', 'lms_admin', 'principal_admin'])) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('learner.my-courses');
    })->name('dashboard');

    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware(['role:super_admin|lms_admin|principal_admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        
        // Master Data Phase 1
        Route::resource('entities', \App\Http\Controllers\Admin\EntityController::class)->except(['create', 'edit', 'show']);
        Route::resource('regions', \App\Http\Controllers\Admin\RegionController::class)->except(['create', 'edit', 'show']);
        Route::resource('areas', \App\Http\Controllers\Admin\AreaController::class)->except(['create', 'edit', 'show']);
        Route::resource('principals', PrincipalController::class)->except(['create', 'edit', 'show']);
        Route::resource('positions', PositionController::class)->except(['create', 'edit', 'show']);
        Route::resource('departments', DepartmentController::class)->except(['create', 'edit', 'show']);
        
        Route::resource('users', UserController::class);
        Route::resource('courses', CourseController::class);
        Route::resource('assignment_rules', AssignmentRuleController::class);
        Route::resource('question_banks', QuestionBankController::class);
        Route::post('question_banks/{bank}/questions', [QuestionBankController::class, 'storeQuestion'])->name('question_banks.questions.store');
        Route::delete('question_banks/{bank}/questions/{question}', [QuestionBankController::class, 'destroyQuestion'])->name('question_banks.questions.destroy');
        Route::resource('assessments', AssessmentController::class);
        Route::get('imports/template', [ImportController::class, 'downloadTemplate'])->name('imports.template');
          Route::post('imports/preview', [ImportController::class, 'preview'])->name('imports.preview');
        Route::post('imports/create-batch', [ImportController::class, 'storeCreate'])->name('imports.storeCreate');
        Route::post('imports/update-batch', [ImportController::class, 'storeUpdate'])->name('imports.storeUpdate');
        Route::resource('imports', ImportController::class)->only(['index']);
        Route::post('courses/{course}/modules', [ModuleController::class, 'store'])->name('courses.modules.store');
        Route::delete('courses/{course}/modules/{module}', [ModuleController::class, 'destroy'])->name('courses.modules.destroy');
        Route::post('modules/{module}/lessons', [LessonController::class, 'store'])->name('modules.lessons.store');
        Route::delete('modules/{module}/lessons/{lesson}', [LessonController::class, 'destroy'])->name('modules.lessons.destroy');
    });

    // Learner Routes
    Route::prefix('learner')->name('learner.')->middleware(['role:learner'])->group(function () {
        Route::get('/my-courses', [LearnerCourseController::class, 'index'])->name('my-courses');
        Route::get('/courses/{course}', [LearnerCourseController::class, 'show'])->name('courses.show');
        Route::post('/progress', [ProgressController::class, 'update'])->name('progress.update');
        Route::get('/courses/{course}/assessments/{assessment}', [LearnerAssessmentController::class, 'show'])->name('courses.assessments.show');
        Route::post('/courses/{course}/attempts/{attempt}', [LearnerAssessmentController::class, 'submit'])->name('courses.attempts.submit');
    });
});

require __DIR__.'/auth.php';















