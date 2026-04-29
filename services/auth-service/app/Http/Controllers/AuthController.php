<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // 1. Fungsi untuk melempar user ke GitHub
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    // 2. Fungsi untuk menangkap data balik dari GitHub
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
                'avatar' => $githubUser->avatar, // Simpan Foto Profil
                'auth_provider' => 'github',
                'password' => bcrypt(Str::random(16)), // Password dummy
            ]);

            // Sementara return data user dulu untuk ngetes
            return response()->json([
                'message' => 'Login Berhasil',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal Login GitHub'], 500);
        }
    }
}