<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
            'permissions' => 'array',
        ]);

        $permissions = $request->input('permissions', []);

        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', 'Hak akses berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name|max:255',
        ], [
            'name.required' => 'Nama peran wajib diisi.',
            'name.unique' => 'Nama peran sudah ada.',
            'name.max' => 'Nama peran maksimal 255 karakter.',
        ]);

        Role::create(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Peran baru berhasil ditambahkan.');
    }

    public function destroy(Role $role)
    {
        if ($role->name == 'Super Admin' || $role->name == 'Admin' || $role->name == 'User') {
            return redirect()->route('roles.index')->with('error', 'Peran bawaan sistem tidak dapat dihapus.');
        }

        // Check if role is assigned to any users before deleting (optional, depends on requirement, but safe to do)
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')->with('error', 'Peran tidak dapat dihapus karena masih digunakan oleh pengguna.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Peran berhasil dihapus.');
    }
}
