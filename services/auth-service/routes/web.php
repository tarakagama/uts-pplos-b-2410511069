<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // Pindahkan ke atas sini

Route::get('/', function () {
    return view('welcome');
});

// Kelompokkan route auth di bawah
Route::get('auth/github', [AuthController::class, 'redirectToProvider']);
Route::get('auth/github/callback', [AuthController::class, 'handleProviderCallback']);