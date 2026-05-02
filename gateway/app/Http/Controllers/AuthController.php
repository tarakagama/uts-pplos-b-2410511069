<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    // Base URL ke Auth-Service
    private $authServiceUrl = 'http://127.0.0.1:8000/api';

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $response = Http::post("{$this->authServiceUrl}/login", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function me(Request $request)
    {
        // Ambil token dari Header Request si user
        $token = $request->bearerToken();

        // Teruskan token ke Auth-Service
        $response = Http::withToken($token)->get("{$this->authServiceUrl}/me");
        return response()->json($response->json(), $response->status());
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        // Tembak Logout ke Auth-Service
        $response = Http::withToken($token)->post("{$this->authServiceUrl}/logout");
        return response()->json($response->json(), $response->status());
    }

    public function refresh(Request $request)
    {
        $token = $request->bearerToken();

        $response = Http::withToken($token)->post("{$this->authServiceUrl}/refresh");
        return response()->json($response->json(), $response->status());
    }
}