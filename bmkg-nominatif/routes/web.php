<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrAccessController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryHistoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard (if authenticated) or login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authenticated Routes
Route::middleware(['auth', 'active.user', 'last.login'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Data Pegawai (Read for both Admin and User, Write for Admin via Policies)
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    // Riwayat Kenaikan Gaji
    Route::get('/salary-history', [SalaryHistoryController::class, 'index'])->name('salary-history.index');

    // Employee History Sub-resources
    Route::post('/employees/{id}/educations', [EmployeeController::class, 'storeEducation'])->name('employees.educations.store');
    Route::post('/employees/{id}/ranks', [EmployeeController::class, 'storeRank'])->name('employees.ranks.store');
    Route::post('/employees/{id}/positions', [EmployeeController::class, 'storePosition'])->name('employees.positions.store');
    Route::post('/employees/{id}/families', [EmployeeController::class, 'storeFamily'])->name('employees.families.store');
    Route::post('/employees/{id}/salary-history', [EmployeeController::class, 'storeSalaryHistory'])->name('employees.salary-history.store');
    Route::post('/employees/{id}/salary-history/import-pdf', [EmployeeController::class, 'importSalaryPdf'])->name('employees.salary-history.import-pdf');
    Route::delete('/employees/{employeeId}/salary-history/{historyId}', [EmployeeController::class, 'destroySalaryHistory'])->name('employees.salary-history.destroy');

    // Export Excel
    Route::get('/export/excel', [ExportController::class, 'excel'])->name('export.excel');

    // AJAX: Daftar program studi berdasarkan jenjang pendidikan
    Route::get('/api/majors', [EmployeeController::class, 'getMajors'])->name('api.majors');

    // AJAX: Global search pegawai (header search bar)
    Route::get('/api/employees/search', [EmployeeController::class, 'searchGlobal'])->name('api.employees.search');

    // AJAX: Notifikasi data pegawai tidak lengkap
    Route::get('/api/employees/incomplete', [EmployeeController::class, 'incompleteData'])->name('api.employees.incomplete');

    // Master Data (Read-only view)
    Route::get('/master-data', [MasterDataController::class, 'index'])->name('master.index');

    // Akses Mobile QR Code
    Route::get('/qr-access', [QrAccessController::class, 'index'])->name('qr-access.index');

    // ADMIN ONLY ROUTES
    Route::middleware(['role:admin'])->group(function () {

        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('/users/{id}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

        // Role Management
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

        // Audit Logs
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

        // Quick-add master data dari halaman filter
        Route::post('/master-data/educations', [MasterDataController::class, 'storeEducation'])->name('master.educations.store');
        Route::post('/master-data/ranks', [MasterDataController::class, 'storeRank'])->name('master.ranks.store');
        Route::post('/master-data/positions', [MasterDataController::class, 'storePosition'])->name('master.positions.store');
        Route::post('/master-data/work-units', [MasterDataController::class, 'storeWorkUnit'])->name('master.work-units.store');
        Route::post('/master-data/employment-statuses', [MasterDataController::class, 'storeEmploymentStatus'])->name('master.employment-statuses.store');
    });

});

require __DIR__.'/auth.php';
