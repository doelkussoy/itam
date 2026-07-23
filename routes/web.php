<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified', 'permission:menu_dashboard'])->name('dashboard');
Route::get('/dashboard/activities', [App\Http\Controllers\DashboardController::class, 'activities'])->middleware(['auth'])->name('dashboard.activities');

Route::get('lang/{lang}', [App\Http\Controllers\LanguageController::class, 'switchLang'])->name('lang.switch');
Route::get('theme/{theme}', [App\Http\Controllers\ThemeController::class, 'switchTheme'])->name('theme.switch');

// Public route for viewing asset details (e.g. from QR code)
Route::get('assets/{asset}', [App\Http\Controllers\AssetController::class, 'show'])
    ->name('assets.show')
    ->where('asset', '[0-9]+');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Roles & Permissions
    Route::middleware(['permission:menu_roles'])->group(function () {
        Route::resource('roles', App\Http\Controllers\RoleController::class)->only(['index', 'edit', 'update']);
    });

    Route::middleware(['permission:menu_departments'])->group(function () {
        Route::post('departments/import', [App\Http\Controllers\DepartmentController::class, 'importExcel'])->name('departments.import');
        Route::get('departments/export', [App\Http\Controllers\DepartmentController::class, 'exportExcel'])->name('departments.export');
        Route::resource('departments', App\Http\Controllers\DepartmentController::class);
    });

    Route::middleware(['permission:menu_vendors'])->group(function () {
        Route::resource('vendors', App\Http\Controllers\VendorController::class);
    });

    Route::middleware(['permission:menu_brands'])->group(function () {
        Route::post('brands/import', [App\Http\Controllers\BrandController::class, 'importExcel'])->name('brands.import');
        Route::get('brands/export', [App\Http\Controllers\BrandController::class, 'exportExcel'])->name('brands.export');
        Route::resource('brands', App\Http\Controllers\BrandController::class);
    });

    Route::middleware(['permission:menu_locations'])->group(function () {
        Route::post('locations/import', [App\Http\Controllers\LocationController::class, 'importExcel'])->name('locations.import');
        Route::get('locations/export', [App\Http\Controllers\LocationController::class, 'exportExcel'])->name('locations.export');
        Route::resource('locations', App\Http\Controllers\LocationController::class);
    });

    Route::middleware(['permission:menu_categories'])->group(function () {
        Route::post('categories/import', [App\Http\Controllers\CategoryController::class, 'importExcel'])->name('categories.import');
        Route::get('categories/export', [App\Http\Controllers\CategoryController::class, 'exportExcel'])->name('categories.export');
        Route::resource('categories', App\Http\Controllers\CategoryController::class);
    });

    Route::middleware(['permission:menu_employees'])->group(function () {
        Route::post('employees/import', [App\Http\Controllers\EmployeeController::class, 'importExcel'])->name('employees.import');
        Route::get('employees/export', [App\Http\Controllers\EmployeeController::class, 'exportExcel'])->name('employees.export');
        Route::patch('employees/{emp}/status', [App\Http\Controllers\EmployeeController::class, 'updateStatus'])->name('employees.updateStatus');
        Route::resource('employees', App\Http\Controllers\EmployeeController::class);
    });

    Route::middleware(['permission:menu_users'])->group(function () {
        Route::resource('users', App\Http\Controllers\UserController::class);
    });

    Route::middleware(['permission:menu_vlans'])->group(function () {
        Route::patch('vlans/{vlan}/status', [App\Http\Controllers\VlanController::class, 'updateStatus'])->name('vlans.updateStatus');
        Route::resource('vlans', App\Http\Controllers\VlanController::class);
    });
    
    Route::middleware(['permission:menu_software_licenses'])->group(function () {
        Route::resource('software_licenses', App\Http\Controllers\SoftwareLicenseController::class);
    });
    
    Route::middleware(['permission:menu_password_vaults'])->group(function () {
        Route::resource('password_vaults', App\Http\Controllers\PasswordVaultController::class);
    });

    Route::middleware(['permission:menu_settings'])->group(function () {
        Route::get('settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings/update-all', [App\Http\Controllers\SettingController::class, 'updateAll'])->name('settings.updateAll');
    });

    Route::middleware(['permission:menu_assets'])->group(function () {
        Route::post('assets/import', [App\Http\Controllers\AssetController::class, 'importExcel'])->name('assets.import');
        Route::get('assets/export', [App\Http\Controllers\AssetController::class, 'exportExcel'])->name('assets.export');
        Route::get('assets/generate-tag', [App\Http\Controllers\AssetController::class, 'generateTag'])->name('assets.generate-tag');
        Route::patch('assets/{asset}/status', [App\Http\Controllers\AssetController::class, 'updateStatus'])->name('assets.updateStatus');
        Route::resource('assets', App\Http\Controllers\AssetController::class)->except(['show']);
    });

    Route::middleware(['permission:menu_assignments'])->group(function () {
        Route::post('assignments/{assignment}/return', [App\Http\Controllers\AssetAssignmentController::class, 'returnAsset'])->name('assignments.return');
        Route::patch('assignments/{assignment}/status', [App\Http\Controllers\AssetAssignmentController::class, 'updateStatus'])->name('assignments.updateStatus');
        Route::resource('assignments', App\Http\Controllers\AssetAssignmentController::class);
    });

    Route::middleware(['permission:menu_maintenances'])->group(function () {
        Route::post('maintenances/{maintenance}/complete', [App\Http\Controllers\MaintenanceController::class, 'complete'])->name('maintenances.complete');
        Route::get('maintenances/export', [App\Http\Controllers\MaintenanceController::class, 'exportExcel'])->name('maintenances.export');
        Route::patch('maintenances/{maintenance}/status', [App\Http\Controllers\MaintenanceController::class, 'updateStatus'])->name('maintenances.updateStatus');
        Route::resource('maintenances', App\Http\Controllers\MaintenanceController::class);
    });

    Route::middleware(['permission:menu_tickets'])->group(function () {
        Route::get('tickets/export', [App\Http\Controllers\TicketController::class, 'exportExcel'])->name('tickets.export');
        Route::patch('tickets/{ticket}/status', [App\Http\Controllers\TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
        Route::resource('tickets', App\Http\Controllers\TicketController::class);
    });

    Route::middleware(['permission:menu_ips'])->group(function () {
        Route::get('ips/export', [App\Http\Controllers\IpAddressController::class, 'exportExcel'])->name('ips.export');
        Route::post('ips/{ip}/ping', [App\Http\Controllers\IpAddressController::class, 'ping'])->name('ips.ping');
        Route::patch('ips/{ip}/status', [App\Http\Controllers\IpAddressController::class, 'updateStatus'])->name('ips.updateStatus');
        Route::resource('ips', App\Http\Controllers\IpAddressController::class);
    });
});

require __DIR__ . '/auth.php';