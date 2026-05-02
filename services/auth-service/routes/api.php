<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// --- LOGIN MANUAL ---
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});


// --- LOGIN GITHUB ---
Route::get('/login/github', function () {
    return Socialite::driver('github')->stateless()->redirect();
});

Route::get('/login/github/callback', function () {
    try {

        $githubUser = Socialite::driver('github')->stateless()->user();
        
        $user = User::updateOrCreate([
            'email' => $githubUser->email,
        ], [
            'name' => $githubUser->name ?? $githubUser->nickname,
            'github_id' => $githubUser->id,
            'auth_provider' => 'github',
            'password' => bcrypt(str()->random(16)), 
        ]);

        $token = Auth::guard('api')->login($user);

        return redirect("http://127.0.0.1:5000/api/auth/me?token={$token}");

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Gagal login via GitHub',
            'error' => $e->getMessage()
        ], 500);
    }
});


// --- PROTECTED ROUTES ---
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
});