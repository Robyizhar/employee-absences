<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\EmployeeController;

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
    });

    Route::prefix('absence')->group(function () {
        Route::get('/', [AbsenceController::class, 'index']);
        Route::get('/list', [AbsenceController::class, 'list']);
        // Route::get('/refresh', [AbsenceController::class, 'refreshEmployees']);
    });

});

require __DIR__.'/auth.php';
