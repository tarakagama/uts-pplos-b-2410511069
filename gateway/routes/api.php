<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

// 1. RUTE PUBLIK
// Middleware throttle:60
Route::middleware('throttle:60,1')->group(function () {

    // Login Manual
    Route::post('/login', [AuthController::class, 'login']);
            
    // Login GitHub Redirect
    Route::get('/auth/github', function () {
        return redirect('http://127.0.0.1:8000/api/login/github');
    });

    // Proxy ke Field Service 
    Route::get('/fields', function (Request $request) {
        // Meneruskan query parameter seperti ?page=1&per_page=10
        $response = Http::get(env('FIELD_SERVICE_URL') . '/api/fields', $request->query());
        return response()->json($response->json(), $response->status());
    });
});

// 2. RUTE TERPROTEKSI
Route::middleware(['throttle:60,1', 'jwt.gateway'])->group(function () {
    
    // Proxy ke Booking Service
    Route::post('/bookings', function (Request $request) {
        $response = Http::post(env('BOOKING_SERVICE_URL') . '/api/bookings', $request->all());
        return response()->json($response->json(), $response->status());
    });

    // Get Profile (Me)
    Route::get('/auth/me', function (Request $request) {
        $response = Http::withToken($request->bearerToken())
            ->get(env('AUTH_SERVICE_URL') . '/api/me');
        return response()->json($response->json(), $response->status());
    });

    // Logout
    Route::post('/logout', function (Request $request) {
        $response = Http::withToken($request->bearerToken())
            ->post(env('AUTH_SERVICE_URL') . '/api/logout');
        return response()->json($response->json(), $response->status());
    });

    // Refresh Token
    Route::post('/refresh', function (Request $request) {
        $response = Http::withToken($request->bearerToken())
            ->post(env('AUTH_SERVICE_URL') . '/api/refresh');
        return response()->json($response->json(), $response->status());
    });
});