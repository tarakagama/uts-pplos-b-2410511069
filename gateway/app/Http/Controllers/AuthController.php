<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Wajib untuk nembak API lain

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input dari user
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            // Nembak ke Auth-Service (Port 8000)
            $response = Http::post('http://127.0.0.1:8000/api/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            // Teruskan respon dari Auth-Service ke User
            return response()->json($response->json(), $response->status());
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Auth Service tidak terjangkau',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}