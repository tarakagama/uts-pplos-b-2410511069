<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Login via GitHub
Route::get('/login/github', [AuthController::class, 'redirectToProvider']);
Route::get('/login/github/callback', [AuthController::class, 'handleProviderCallback']);

// --- RUTE TERPROTEKSI

Route::middleware('auth:api')->group(function () {
    
    // Logout (Token Invalidation)
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Refresh Token
    Route::post('/refresh', [AuthController::class, 'refresh']);
    
    // Cek Data Profil Saya
    Route::get('/me', function () {
        return response()->json(auth()->user());
    });
});