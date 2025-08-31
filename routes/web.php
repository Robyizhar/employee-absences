<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AbsenceController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('company')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('company');
        Route::get('/list', [CompanyController::class, 'list']);
    });

    Route::prefix('absence')->group(function () {
        Route::get('/', [AbsenceController::class, 'index'])->name('dashboard');
    });

});

require __DIR__.'/auth.php';
