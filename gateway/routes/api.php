<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;


// 1. KELOMPOK RUTE PUBLIK & AUTH PROXY
Route::middleware('throttle:60,1')->group(function () {

    

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/logout', function (Request $request) {
        $response = Http::withToken($request->bearerToken())
            ->post(env('AUTH_SERVICE_URL') . '/api/logout');
        return response()->json($response->json(), $response->status());
    });

    Route::post('/refresh', function (Request $request) {
        $response = Http::withToken($request->bearerToken())
            ->post(env('AUTH_SERVICE_URL') . '/api/refresh');
        return response()->json($response->json(), $response->status());
    });

    Route::get('/auth/me', function (Request $request) {
        $response = Http::withToken($request->bearerToken())
            ->get(env('AUTH_SERVICE_URL') . '/api/me');
        return response()->json($response->json(), $response->status());
    });

    Route::get('/auth/github', function () {
        return redirect(env('AUTH_SERVICE_URL') . '/api/login/github');
    });



    Route::get('/fields', function (Request $request) {
        $response = Http::get(env('FIELD_SERVICE_URL') . '/api/fields', $request->query());
        return response()->json($response->json(), $response->status());
    });

    Route::get('/fields/{id}', function ($id) {
        $response = Http::get(env('FIELD_SERVICE_URL') . "/api/fields/{$id}");
        return response()->json($response->json(), $response->status());
    });
});

    Route::post('/bookings', function (Request $request) {
        $response = Http::withToken($request->bearerToken())
            ->post(env('BOOKING_SERVICE_URL') . '/api/bookings', $request->all());
        
        return response()->json($response->json(), $response->status());
    });

    Route::get('/bookings', function (Request $request) {
        $response = Http::withToken($request->bearerToken())
            ->get(env('BOOKING_SERVICE_URL') . '/api/bookings');
        return response()->json($response->json(), $response->status());
    });

// 2. KELOMPOK RUTE TERPROTEKSI (MEMBUTUHKAN LOGIN)
Route::middleware(['throttle:60,1', 'jwt.gateway'])->group(function () {
    

});