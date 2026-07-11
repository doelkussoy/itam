<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'view dashboard',
            'manage master data',
            'manage employees',
            'manage assets',
            'manage assignments',
            'manage maintenance',
            'manage tickets',
            'manage network',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // create roles and assign created permissions

        // User (Operation only)
        $roleUser = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);
        $roleUser->syncPermissions([
            'view dashboard',
            'manage assets',
            'manage assignments',
            'manage maintenance',
            'manage tickets',
            'manage network'
        ]);

        // Admin
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $roleAdmin->givePermissionTo(Permission::all());

        // Super Admin
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $roleSuperAdmin->givePermissionTo(Permission::all());

        // Create Default Users
        $superAdmin = User::firstOrCreate([
            'email' => 'superadmin@cba.co.id'
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('Super Admin');

        $admin = User::firstOrCreate([
            'email' => 'admin@cba.co.id'
        ], [
            'name' => 'Admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Admin');

        $user = User::firstOrCreate([
            'email' => 'user@cba.co.id'
        ], [
            'name' => 'User Operation',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('User');
    }
}
