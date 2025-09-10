<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FingerspotController;

Route::post('/store', [FingerspotController::class, 'store']);
Route::post('/attendances', [FingerspotController::class, 'attendances']);
Route::post('/userinfo', [FingerspotController::class, 'userInfo']);

