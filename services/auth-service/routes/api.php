<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Rute untuk Login
Route::post('/login', [AuthController::class, 'login']);

// Rute yang butuh Token JWT
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
});