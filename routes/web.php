<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AbsenceController;

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::prefix('company')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('company');
        Route::get('/list', [CompanyController::class, 'list']);
    });

    Route::prefix('absence')->group(function () {
        Route::get('/', [AbsenceController::class, 'index']);
    });

});

require __DIR__.'/auth.php';
