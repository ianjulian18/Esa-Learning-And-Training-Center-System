<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Entity;
use App\Models\Principal;
use App\Models\Region;
use App\Models\Area;
use App\Models\Position;
use App\Models\Department;
use App\Models\EmploymentHistory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\AssignmentEngine;
use Spatie\Permission\Models\Role;

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
            'entities' => Entity::all(),
            'principals' => Principal::all(),
            'regions' => Region::all(),
            'areas' => Area::all(),
            'positions' => Position::all(),
            'departments' => Department::all(),
            'roles' => Role::all(),
        ]);
    }

    public function store(Request )
    {
        $validated = $request->validate([
            'nik' => 'required|string|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|exists:roles,name',
            'nip' => 'required|string',
            'entity_id' => 'required|exists:entities,id',
            'principal_id' => 'required|exists:principals,id',
            'region_id' => 'nullable|exists:regions,id',
            'area_id' => 'nullable|exists:areas,id',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'nullable|exists:departments,id',
            'start_date' => 'required|date',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'nik' => $validated['nik'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole($validated['role']);

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
        });

        return redirect()->route('admin.users.index')->with('success', 'User and Employment created successfully.');
    }

    public function edit(User $user)
    {
        $user->load('currentEmployment', 'roles');
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'entities' => Entity::all(),
            'principals' => Principal::all(),
            'regions' => Region::all(),
            'areas' => Area::all(),
            'positions' => Position::all(),
            'departments' => Department::all(),
            'roles' => Role::all(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nik' => 'required|string|unique:users,nik,' . $user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|string|exists:roles,name',
            'status' => 'required|in:ACTIVE,INACTIVE',
            'nip' => 'required|string',
            'entity_id' => 'required|exists:entities,id',
            'principal_id' => 'required|exists:principals,id',
            'region_id' => 'nullable|exists:regions,id',
            'area_id' => 'nullable|exists:areas,id',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'nullable|exists:departments,id',
            'start_date' => 'required|date',
        ]);

        DB::transaction(function () use ($validated, $user) {
            $user->update([
                'nik' => $validated['nik'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'status' => $validated['status'] ?? 'ACTIVE'
            ]);

            if (!empty($validated['password'])) {
                $user->update(['password' => Hash::make($validated['password'])]);
            }

            $user->syncRoles([$validated['role']]);

            // Check Principal Change
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

            // Update Employment History
            $employment = $user->currentEmployment;
            
            // Check if any employment fields changed to warrant a new history entry
            $needsNewHistory = false;
            if ($employment) {
                if ($employment->nip != $validated['nip'] ||
                    $employment->entity_id != $validated['entity_id'] ||
                    $employment->principal_id != $validated['principal_id'] ||
                    $employment->region_id != $validated['region_id'] ||
                    $employment->area_id != $validated['area_id'] ||
                    $employment->position_id != $validated['position_id'] ||
                    $employment->department_id != $validated['department_id'] ||
                    $validated['status'] === 'INACTIVE'
                ) {
                    $needsNewHistory = true;
                }
            } else {
                $needsNewHistory = true;
            }

            if ($needsNewHistory) {
                if ($employment) {
                    $employment->update([
                        'status' => 'INACTIVE',
                        'end_date' => now()
                    ]);
                }
                
                if ($validated['status'] === 'ACTIVE') {
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
                }
            } else {
                // Just update the start date if they only changed that
                $employment->update([
                    'start_date' => $validated['start_date']
                ]);
            }

            AssignmentEngine::evaluateUser($user);
        });

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
