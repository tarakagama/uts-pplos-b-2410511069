<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // 1. Fungsi melempar user ke GitHub
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    // 2. Callback GitHub + Generate JWT
    public function handleProviderCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();

            // Sesuai syarat poin 5: Mapping ke user lokal
            $user = User::updateOrCreate([
                'github_id' => $githubUser->id,
            ], [
                'name' => $githubUser->name ?? $githubUser->nickname,
                'email' => $githubUser->email,
                'avatar' => $githubUser->avatar,
                'auth_provider' => 'github',
                'password' => bcrypt(Str::random(16)), 
            ]);

            // GENERATE TOKEN JWT buat user ini
            $token = auth()->login($user);

            return $this->respondWithToken($token);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal Login GitHub: ' . $e->getMessage()], 500);
        }
    }

    // 3. Fungsi Refresh Token (Syarat Poin 4)
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    // 4. Fungsi Logout (Syarat Poin 4)
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    // 5. Helper format response token (Wajib JSON sesuai standar REST API)
    protected function respondWithToken($token)
    {
        return response()->json([
            'message' => 'Login Berhasil',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60, // 15 menit
            'user' => auth()->user()
        ]);
    }
}