<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;


// Endpoint untuk melihat semua daftar booking
Route::get('/bookings', [BookingController::class, 'index']);

// Endpoint untuk membuat booking baru
Route::post('/bookings', [BookingController::class, 'store']);

// Endpoint untuk membatalkan/menghapus booking
Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);