<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderBy('created_at', 'desc')->paginate(10);
        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'flash' => request()->session()->get('flash')
        ]);
    }

    public function create()
    {
        $roles = Role::all();
        return Inertia::render('Admin/Users/Create', [
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nik' => 'required|string|max:50|unique:users',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:ACTIVE,INACTIVE'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik,
            'status' => $request->status,
            'password' => Hash::make('password123'),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully. Default password is password123');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nik' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:ACTIVE,INACTIVE'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik,
            'status' => $request->status,
        ]);

        // Sync role (since a user should only have 1 role in this LMS)
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }
        
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}

