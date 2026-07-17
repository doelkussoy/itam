<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereDoesntHave('roles', function($q){
            $q->where('name', 'Super Admin');
        });

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('username', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->orderBy('name')->paginate(10)->appends($request->all());
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'Super Admin')->get();
        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            $user->assignRole($request->role);

            return redirect()->route('users.index')->with('success', __('messages.created_success') ?? 'User created successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        // Protect from editing Super Admin
        if ($user->hasRole('Super Admin')) {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::where('name', '!=', 'Super Admin')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        if ($user->hasRole('Super Admin')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);
            $user->syncRoles([$request->role]);

            return redirect()->route('users.index')->with('success', __('messages.updated_success') ?? 'User updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('Super Admin') || $user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete this user.');
        }

        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', __('messages.deleted_success') ?? 'User deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
