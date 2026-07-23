<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

app()[PermissionRegistrar::class]->forgetCachedPermissions();

$permissions = [
    'menu_dashboard',
    'menu_assets',
    'menu_assignments',
    'menu_tickets',
    'menu_maintenances',
    'menu_departments',
    'menu_brands',
    'menu_locations',
    'menu_categories',
    'menu_vendors',
    'menu_employees',
    'menu_users',
    'menu_vlans',
    'menu_ips',
    'menu_software_licenses',
    'menu_password_vaults',
    'menu_settings',
    'menu_roles'
];

foreach ($permissions as $permission) {
    Permission::firstOrCreate(['name' => $permission]);
}

$adminRole = Role::where('name', 'Admin')->first();
if ($adminRole) {
    $adminRole->syncPermissions(Permission::where('name', '!=', 'menu_roles')->get());
}

$userRole = Role::where('name', 'User')->first();
if ($userRole) {
    $userPermissions = [
        'menu_dashboard',
        'menu_assets',
        'menu_assignments',
        'menu_tickets',
        'menu_maintenances',
        'menu_ips'
    ];
    $userRole->syncPermissions($userPermissions);
}

echo "Permissions seeded successfully!\n";
