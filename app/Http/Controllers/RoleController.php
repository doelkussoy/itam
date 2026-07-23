<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        // Don't let them edit Super Admin permissions
        $roles = Role::where('name', '!=', 'Super Admin')->get();
        return view('roles.index', compact('roles'));
    }

    public function edit(Role $role)
    {
        if ($role->name == 'Super Admin') {
            abort(403, 'Super Admin role cannot be edited.');
        }

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name == 'Super Admin') {
            abort(403, 'Super Admin role cannot be edited.');
        }

        $request->validate([
            'permissions' => 'array'
        ]);

        $permissions = $request->input('permissions', []);
        
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', 'Hak akses berhasil diperbarui.');
    }
}
