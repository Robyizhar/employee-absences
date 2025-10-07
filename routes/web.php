<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::prefix('company')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('company');
        Route::get('/list', [CompanyController::class, 'list']);
    });

    Route::prefix('employee')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('employee');
        Route::get('/list', [EmployeeController::class, 'list']);
        Route::get('/refresh', [EmployeeController::class, 'refreshEmployees']);
        Route::put('/{employee}', [EmployeeController::class, 'update'])->name('employee.update');
    });

    Route::prefix('absence')->group(function () {
        Route::get('/', [AbsenceController::class, 'index']);
        Route::get('/list', [AbsenceController::class, 'list']);

        Route::get('/recapitulation', [AbsenceController::class, 'recapitulation']);
        Route::get('/recapitulation/list', [AbsenceController::class, 'recapitulationList']);
        Route::get('/recapitulation/export', [AbsenceController::class, 'export']);
        // Route::get('/refresh', [AbsenceController::class, 'refreshAbsences']);
    });

    Route::prefix('department')->group(function () {
        Route::get('/', [DepartmentController::class, 'index']);
        Route::get('/list', [DepartmentController::class, 'list']);
        // Route::get('/refresh', [DepartmentController::class, 'refreshEmployees']);
        Route::post('/store', [DepartmentController::class, 'store'])->name('department.store');
        Route::post('/update/{department}', [DepartmentController::class, 'update'])->name('department.update');
        Route::delete('/delete/{department}', [DepartmentController::class, 'destroy'])->name('department.destroy');
    });

    Route::prefix('permission')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permission');
        Route::get('/list', [PermissionController::class, 'list']);
        // Route::put('/{permission}', [PermissionController::class, 'update'])->name('employee.update');
    });

    Route::prefix('role')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('role');
        Route::get('/list', [RoleController::class, 'list']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/{role}', [RoleController::class, 'show']);
        Route::put('/{role}', [RoleController::class, 'update']);
        Route::delete('/{role}', [RoleController::class, 'destroy']);
    });

});

require __DIR__.'/auth.php';
