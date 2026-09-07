<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssignmentRule;
use App\Models\Principal;
use App\Models\Course;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\AssignmentEngine;

class AssignmentRuleController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/AssignmentRules/Index', [
            'rules' => AssignmentRule::with(['principal', 'course', 'department', 'positions'])->latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/AssignmentRules/Create', [
            'principals' => Principal::all(),
            'courses' => Course::all(),
            'departments' => Department::all(),
            'positions' => Position::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'principal_id' => 'required|exists:principals,id',
            'course_id' => 'required|exists:courses,id',
            'department_id' => 'nullable|exists:departments,id',
            'position_ids' => 'required|array', // ALL POSITIONS if contains 'all' or specific IDs
            'position_ids.*' => 'exists:positions,id',
            'effective_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:effective_date',
        ]);

        $rule = AssignmentRule::create([
            'principal_id' => $validated['principal_id'],
            'course_id' => $validated['course_id'],
            'department_id' => $validated['department_id'],
            'effective_date' => $validated['effective_date'],
            'expiry_date' => $validated['expiry_date'],
            'status' => 'ACTIVE'
        ]);

        $rule->positions()->sync($validated['position_ids']);

        // Trigger Assignment Engine here in the future
        AssignmentEngine::evaluateRule($rule);

        return redirect()->route('admin.assignment_rules.index')->with('success', 'Assignment Rule created. Engine will evaluate users.');
    }
}

