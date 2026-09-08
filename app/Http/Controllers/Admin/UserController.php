<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Principal;
use App\Models\Position;
use App\Models\Department;
use App\Models\EmploymentHistory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Services\AssignmentEngine;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => User::with('currentEmployment.principal', 'currentEmployment.position', 'roles')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Users/Create', [
            'principals' => Principal::all(),
            'positions' => Position::all(),
            'departments' => Department::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'nip' => 'required|string',
            'principal_id' => 'required|exists:principals,id',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'nullable|exists:departments,id',
            'start_date' => 'required|date',
        ]);

        $user = User::create([
            'nik' => $validated['nik'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('learner');

        EmploymentHistory::create([
            'user_id' => $user->id,
            'nip' => $validated['nip'],
            'principal_id' => $validated['principal_id'],
            'position_id' => $validated['position_id'],
            'department_id' => $validated['department_id'],
            'start_date' => $validated['start_date'],
            'status' => 'ACTIVE',
            'source' => 'MANUAL'
        ]);

        AssignmentEngine::evaluateUser($user);
        return redirect()->route('admin.users.index')->with('success', 'User and Employment created.');
    }

    public function edit(User $user)
    {
        $user->load('currentEmployment');
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'principals' => Principal::all(),
            'positions' => Position::all(),
            'departments' => Department::all(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'nip' => 'required|string',
            'principal_id' => 'required|exists:principals,id',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'nullable|exists:departments,id',
            'start_date' => 'required|date',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // Simplistic approach for edit: update the current employment or create new if not exist
        $employment = $user->currentEmployment;
        if ($employment) {
            $employment->update([
                'nip' => $validated['nip'],
                'principal_id' => $validated['principal_id'],
                'position_id' => $validated['position_id'],
                'department_id' => $validated['department_id'],
                'start_date' => $validated['start_date'],
            ]);
        } else {
            EmploymentHistory::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'principal_id' => $validated['principal_id'],
                'position_id' => $validated['position_id'],
                'department_id' => $validated['department_id'],
                'start_date' => $validated['start_date'],
                'status' => 'ACTIVE',
                'source' => 'MANUAL'
            ]);
        }

        AssignmentEngine::evaluateUser($user);
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
}
