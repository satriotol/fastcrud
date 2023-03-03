<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->update([
                'last_signin_at' => now(),
                'last_ip_address' => request()->ip()

            ]);
            $token = $request->user()->createToken('my-token')->plainTextToken;
            return ResponseFormatter::success([
                'token' => $token,
                'user' => $request->user()
            ], 'Sukses Login');
        }

        return ResponseFormatter::error('', 'Gagal Login');
    }
}
