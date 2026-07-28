<?php

use App\Http\Controllers\AssetAssignmentController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\IpAddressController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\PasswordVaultController;
use App\Http\Controllers\PicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SoftwareLicenseController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'permission:menu_dashboard'])->name('dashboard');
Route::get('/dashboard/activities', [DashboardController::class, 'activities'])->middleware(['auth'])->name('dashboard.activities');

Route::get('lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.switch');
Route::get('theme/{theme}', [ThemeController::class, 'switchTheme'])->name('theme.switch');

// Public route for viewing asset details (e.g. from QR code)
Route::get('assets/{asset}', [AssetController::class, 'show'])
    ->name('assets.show')
    ->where('asset', '[0-9]+');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Roles & Permissions
    Route::middleware(['permission:menu_roles'])->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);
    });

    Route::middleware(['permission:menu_departments'])->group(function () {
        Route::post('departments/import', [DepartmentController::class, 'importExcel'])->name('departments.import');
        Route::get('departments/export', [DepartmentController::class, 'exportExcel'])->name('departments.export');
        Route::resource('departments', DepartmentController::class);
    });

    Route::middleware(['permission:menu_vendors'])->group(function () {
        Route::resource('vendors', VendorController::class);
    });

    Route::middleware(['permission:menu_brands'])->group(function () {
        Route::post('brands/import', [BrandController::class, 'importExcel'])->name('brands.import');
        Route::get('brands/export', [BrandController::class, 'exportExcel'])->name('brands.export');
        Route::resource('brands', BrandController::class);
    });

    Route::middleware(['permission:menu_locations'])->group(function () {
        Route::post('locations/import', [LocationController::class, 'importExcel'])->name('locations.import');
        Route::get('locations/export', [LocationController::class, 'exportExcel'])->name('locations.export');
        Route::resource('locations', LocationController::class);
    });

    Route::middleware(['permission:menu_categories'])->group(function () {
        Route::post('categories/import', [CategoryController::class, 'importExcel'])->name('categories.import');
        Route::get('categories/export', [CategoryController::class, 'exportExcel'])->name('categories.export');
        Route::resource('categories', CategoryController::class);
    });

    Route::middleware(['permission:menu_employees'])->group(function () {
        Route::post('employees/import', [EmployeeController::class, 'importExcel'])->name('employees.import');
        Route::get('employees/export', [EmployeeController::class, 'exportExcel'])->name('employees.export');
        Route::patch('employees/{emp}/status', [EmployeeController::class, 'updateStatus'])->name('employees.updateStatus');
        Route::resource('employees', EmployeeController::class);
    });

    Route::middleware(['permission:menu_users'])->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::middleware(['permission:menu_vlans'])->group(function () {
        Route::patch('vlans/{vlan}/status', [VlanController::class, 'updateStatus'])->name('vlans.updateStatus');
        Route::resource('vlans', VlanController::class);
    });

    Route::middleware(['permission:menu_software_licenses'])->group(function () {
        Route::resource('software_licenses', SoftwareLicenseController::class);
    });

    Route::middleware(['permission:menu_password_vaults'])->group(function () {
        Route::resource('password_vaults', PasswordVaultController::class);
    });

    Route::middleware(['permission:menu_settings'])->group(function () {
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings/update-all', [SettingController::class, 'updateAll'])->name('settings.updateAll');
    });

    Route::middleware(['permission:menu_assets'])->group(function () {
        Route::post('assets/import', [AssetController::class, 'importExcel'])->name('assets.import');
        Route::get('assets/export', [AssetController::class, 'exportExcel'])->name('assets.export');
        Route::get('assets/generate-tag', [AssetController::class, 'generateTag'])->name('assets.generate-tag');
        Route::patch('assets/{asset}/status', [AssetController::class, 'updateStatus'])->name('assets.updateStatus');
        Route::resource('assets', AssetController::class)->except(['show']);
    });

    Route::middleware(['permission:menu_assignments'])->group(function () {
        Route::post('assignments/{assignment}/return', [AssetAssignmentController::class, 'returnAsset'])->name('assignments.return');
        Route::patch('assignments/{assignment}/status', [AssetAssignmentController::class, 'updateStatus'])->name('assignments.updateStatus');
        Route::resource('assignments', AssetAssignmentController::class);
    });

    Route::middleware(['permission:menu_maintenances'])->group(function () {
        Route::post('maintenances/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('maintenances.complete');
        Route::get('maintenances/export', [MaintenanceController::class, 'exportExcel'])->name('maintenances.export');
        Route::patch('maintenances/{maintenance}/status', [MaintenanceController::class, 'updateStatus'])->name('maintenances.updateStatus');
        Route::resource('maintenances', MaintenanceController::class);
    });

    Route::middleware(['permission:menu_tickets'])->group(function () {
        Route::get('tickets/export', [TicketController::class, 'exportExcel'])->name('tickets.export');
        Route::patch('tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
        Route::resource('tickets', TicketController::class);
        Route::resource('pics', PicController::class)->except(['show', 'edit']);
    });

    Route::middleware(['permission:menu_ips'])->group(function () {
        Route::get('ips/export', [IpAddressController::class, 'exportExcel'])->name('ips.export');
        Route::post('ips/{ip}/ping', [IpAddressController::class, 'ping'])->name('ips.ping');
        Route::patch('ips/{ip}/status', [IpAddressController::class, 'updateStatus'])->name('ips.updateStatus');
        Route::resource('ips', IpAddressController::class);
    });
});

require __DIR__.'/auth.php';
