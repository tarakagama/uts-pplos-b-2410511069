<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

// --- 1. PROXY KE AUTH SERVICE ---
Route::get('/auth/github', function () {
    return Http::get(env('AUTH_SERVICE_URL') . '/api/login/github')->json();
});

// --- 2. PROXY KE FIELD SERVICE ---
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/fields', function () {
        $response = Http::get(env('FIELD_SERVICE_URL') . '/api/fields');
        return response()->json($response->json(), $response->status());
    });
});

// --- 3. PROXY KE BOOKING SERVICE ---
Route::post('/bookings', function (Request $request) {
    $response = Http::post(env('BOOKING_SERVICE_URL') . '/api/bookings', $request->all());
    return response()->json($response->json(), $response->status());
});