<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Certificate;
use Illuminate\Support\Facades\Cache;

class ReportController extends Controller
{
    public function index()
    {
        // Cache the reports for 1 hour to optimize performance (Phase 9)
        $stats = Cache::remember('admin.reports.stats', 3600, function () {
            return [
                'total_users' => User::count(),
                'total_learners' => User::role('Learner')->count(),
                'total_courses' => Course::count(),
                'total_enrollments' => Enrollment::count(),
                'completed_enrollments' => Enrollment::where('status', 'COMPLETED')->count(),
                'total_certificates_issued' => Certificate::count(),
            ];
        });

        // Top courses by enrollment
        $topCourses = Cache::remember('admin.reports.top_courses', 3600, function () {
            return Course::withCount('enrollments')
                ->orderBy('enrollments_count', 'desc')
                ->take(5)
                ->get();
        });

        return Inertia::render('Admin/Reports/Index', [
            'stats' => $stats,
            'topCourses' => $topCourses,
        ]);
    }
}
