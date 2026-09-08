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
            'entity_id' => 'required|exists:entities,id',
            'principal_id' => 'required|exists:principals,id',
            'region_id' => 'nullable|exists:regions,id',
            'area_id' => 'nullable|exists:areas,id',
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
            'entity_id' => $validated['entity_id'],
            'principal_id' => $validated['principal_id'],
            'region_id' => $validated['region_id'] ?? null,
            'area_id' => $validated['area_id'] ?? null,
            'position_id' => $validated['position_id'],
            'department_id' => $validated['department_id'],
            'start_date' => $validated['start_date'],
            'status' => 'ACTIVE',
            'source' => 'MANUAL'
        ]);

        \App\Models\PrincipalHistory::create([
            'user_id' => $user->id,
            'principal_id' => $validated['principal_id'],
            'start_date' => $validated['start_date']
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
            'entity_id' => 'required|exists:entities,id',
            'principal_id' => 'required|exists:principals,id',
            'region_id' => 'nullable|exists:regions,id',
            'area_id' => 'nullable|exists:areas,id',
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

        // Check Principal Change before modifying employment
        $activePrincipal = \App\Models\PrincipalHistory::where('user_id', $user->id)
            ->whereNull('end_date')
            ->orderBy('start_date', 'desc')
            ->first();

        if (!$activePrincipal || $activePrincipal->principal_id != $validated['principal_id']) {
            if ($activePrincipal) {
                $activePrincipal->update(['end_date' => now()]);
            }
            \App\Models\PrincipalHistory::create([
                'user_id' => $user->id,
                'principal_id' => $validated['principal_id'],
                'start_date' => $validated['start_date']
            ]);
        }

        // Close old employment and open new one if principal/position changes
        $employment = $user->currentEmployment;
        if ($employment) {
            // Strictly speaking, if they change roles, we should close the old and open new instead of update
            // We'll update for simplicity unless they changed NIP or Principal, but let's just create a new active history
            $employment->update([
                'status' => 'INACTIVE',
                'end_date' => now()
            ]);
        }
        
        EmploymentHistory::create([
            'user_id' => $user->id,
            'nip' => $validated['nip'],
            'entity_id' => $validated['entity_id'],
            'principal_id' => $validated['principal_id'],
            'region_id' => $validated['region_id'] ?? null,
            'area_id' => $validated['area_id'] ?? null,
            'position_id' => $validated['position_id'],
            'department_id' => $validated['department_id'],
            'start_date' => $validated['start_date'],
            'status' => 'ACTIVE',
            'source' => 'MANUAL'
        ]);

        AssignmentEngine::evaluateUser($user);
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
}


