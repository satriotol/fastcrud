<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        if (Auth::attempt([$field => $credentials['login'], 'password' => $credentials['password']])) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'user' => $user,
                'token' => $token,
            ], 'Login successful');
        }

        return ResponseFormatter::error(null, 'Data Tidak Ditemukan', 401);
    }
}
