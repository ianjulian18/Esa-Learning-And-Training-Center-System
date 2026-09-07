<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\Admin\UserController;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});
use App\Http\Controllers\Admin\CourseController;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('courses', CourseController::class);
    Route::post('courses/{course}/modules', [App\Http\Controllers\Admin\ModuleController::class, 'store'])->name('courses.modules.store');
    Route::put('courses/{course}/modules/{module}', [App\Http\Controllers\Admin\ModuleController::class, 'update'])->name('courses.modules.update');
    Route::delete('courses/{course}/modules/{module}', [App\Http\Controllers\Admin\ModuleController::class, 'destroy'])->name('courses.modules.destroy');
    
    Route::post('courses/{course}/modules/{module}/lessons', [App\Http\Controllers\Admin\LessonController::class, 'store'])->name('courses.modules.lessons.store');
    Route::put('courses/{course}/modules/{module}/lessons/{lesson}', [App\Http\Controllers\Admin\LessonController::class, 'update'])->name('courses.modules.lessons.update');
    Route::delete('courses/{course}/modules/{module}/lessons/{lesson}', [App\Http\Controllers\Admin\LessonController::class, 'destroy'])->name('courses.modules.lessons.destroy');
});

use App\Http\Controllers\LearnerController;
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-courses', [LearnerController::class, 'myCourses'])->name('learner.courses');
    Route::post('/courses/{course}/enroll', [LearnerController::class, 'enroll'])->name('learner.enroll');
    Route::get('/courses/{course}/learn', [LearnerController::class, 'learn'])->name('learner.learn');
    Route::post('/courses/{course}/progress', [LearnerController::class, 'updateProgress'])->name('learner.progress');
    Route::get('/courses/{course}/assessment', [LearnerController::class, 'assessment'])->name('learner.assessment');
    Route::post('/courses/{course}/assessment', [LearnerController::class, 'submitAssessment'])->name('learner.assessment.submit');
});





use App\Http\Controllers\Learner\CertificateController;
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-certificates', [CertificateController::class, 'index'])->name('learner.certificates');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('learner.certificates.download');
});

use App\Http\Controllers\Admin\ReportController;
Route::middleware(['auth', 'verified', 'role:Admin'])->prefix('admin')->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
});
