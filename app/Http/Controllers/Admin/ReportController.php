<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Certificate;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_users' => User::count(),
            'total_learners' => User::count(), // Simplified since we don't know spatie exact setup
            'total_courses' => Course::count(),
            'total_enrollments' => Enrollment::count(),
            'completed_enrollments' => Enrollment::where('status', 'COMPLETED')->count(),
            'total_certificates_issued' => Certificate::count(),
        ];

        $topCourses = Course::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->take(5)
            ->get();

        return Inertia::render('Admin/Reports/Index', [
            'stats' => $stats,
            'topCourses' => $topCourses
        ]);
    }
}
