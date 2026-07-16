<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard/activities', [App\Http\Controllers\DashboardController::class, 'activities'])->middleware(['auth'])->name('dashboard.activities');

Route::get('lang/{lang}', [App\Http\Controllers\LanguageController::class, 'switchLang'])->name('lang.switch');
Route::get('theme/{theme}', [App\Http\Controllers\ThemeController::class, 'switchTheme'])->name('theme.switch');

// Public route for viewing asset details (e.g. from QR code)
Route::get('assets/{asset}', [App\Http\Controllers\AssetController::class, 'show'])->name('assets.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Data, Employees, Settings (Admin & Super Admin Only)
    Route::middleware(['role:Super Admin|Admin'])->group(function () {
        Route::post('departments/import', [App\Http\Controllers\DepartmentController::class, 'importExcel'])->name('departments.import');
        Route::get('departments/export', [App\Http\Controllers\DepartmentController::class, 'exportExcel'])->name('departments.export');
        Route::resource('departments', App\Http\Controllers\DepartmentController::class);
        
        Route::resource('vendors', App\Http\Controllers\VendorController::class);
        
        Route::post('brands/import', [App\Http\Controllers\BrandController::class, 'importExcel'])->name('brands.import');
        Route::get('brands/export', [App\Http\Controllers\BrandController::class, 'exportExcel'])->name('brands.export');
        Route::resource('brands', App\Http\Controllers\BrandController::class);
        
        Route::post('locations/import', [App\Http\Controllers\LocationController::class, 'importExcel'])->name('locations.import');
        Route::get('locations/export', [App\Http\Controllers\LocationController::class, 'exportExcel'])->name('locations.export');
        Route::resource('locations', App\Http\Controllers\LocationController::class);
        
        Route::post('categories/import', [App\Http\Controllers\CategoryController::class, 'importExcel'])->name('categories.import');
        Route::get('categories/export', [App\Http\Controllers\CategoryController::class, 'exportExcel'])->name('categories.export');
        Route::resource('categories', App\Http\Controllers\CategoryController::class);

        Route::post('employees/import', [App\Http\Controllers\EmployeeController::class, 'importExcel'])->name('employees.import');
        Route::get('employees/export', [App\Http\Controllers\EmployeeController::class, 'exportExcel'])->name('employees.export');
        Route::resource('employees', App\Http\Controllers\EmployeeController::class);

        Route::resource('users', App\Http\Controllers\UserController::class);

        Route::resource('vlans', App\Http\Controllers\VlanController::class);
        Route::resource('software_licenses', App\Http\Controllers\SoftwareLicenseController::class);
        Route::resource('password_vaults', App\Http\Controllers\PasswordVaultController::class);

        Route::get('settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings/update-all', [App\Http\Controllers\SettingController::class, 'updateAll'])->name('settings.updateAll');
    });

    // Operation Routes (Admin, Super Admin & User)
    Route::middleware(['role:Super Admin|Admin|User'])->group(function () {
        Route::post('assets/import', [App\Http\Controllers\AssetController::class, 'importExcel'])->name('assets.import');
        Route::get('assets/export', [App\Http\Controllers\AssetController::class, 'exportExcel'])->name('assets.export');
        Route::get('assets/generate-tag', [App\Http\Controllers\AssetController::class, 'generateTag'])->name('assets.generate-tag');
        Route::resource('assets', App\Http\Controllers\AssetController::class)->except(['show']);

        Route::post('assignments/{assignment}/return', [App\Http\Controllers\AssetAssignmentController::class, 'returnAsset'])->name('assignments.return');
        Route::resource('assignments', App\Http\Controllers\AssetAssignmentController::class);

        Route::post('maintenances/{maintenance}/complete', [App\Http\Controllers\MaintenanceController::class, 'complete'])->name('maintenances.complete');
        Route::get('maintenances/export', [App\Http\Controllers\MaintenanceController::class, 'exportExcel'])->name('maintenances.export');
        Route::resource('maintenances', App\Http\Controllers\MaintenanceController::class);

        Route::get('tickets/export', [App\Http\Controllers\TicketController::class, 'exportExcel'])->name('tickets.export');
        Route::resource('tickets', App\Http\Controllers\TicketController::class);

        Route::get('ips/export', [App\Http\Controllers\IpAddressController::class, 'exportExcel'])->name('ips.export');
        Route::post('ips/{ip}/ping', [App\Http\Controllers\IpAddressController::class, 'ping'])->name('ips.ping');
        Route::resource('ips', App\Http\Controllers\IpAddressController::class);
    });
});

require __DIR__.'/auth.php';
