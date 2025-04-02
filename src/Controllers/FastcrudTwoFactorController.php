<?php

namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Satriotol\Fastcrud\Models\FastcrudUser;

class FastcrudTwoFactorController extends Controller
{
    public function show2FASetup(Request $request)
    {
        $google2fa = app('pragmarx.google2fa');

        $user = FastcrudUser::find(auth()->id());
        $google2fa_url = null;
        if (!$user->google2fa_secret || !$user->google2fa_verified) {
            if (!$user->google2fa_secret) {
                $user->generateGoogle2FASecret();
            }

            $google2fa_url = $google2fa->getQRCodeInline(
                env('APP_NAME'),
                $user->email,
                $user->google2fa_secret
            );
        }

        return view('fastcrud::fastcrud_user.2fa', compact('user', 'google2fa_url'));
    }
    public function verify2FA(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $google2fa = app('pragmarx.google2fa');
        $user = FastcrudUser::find(auth()->id());

        if ($google2fa->verifyKey($user->google2fa_secret, $request->otp)) {
            $user->update(['google2fa_verified' => true]);
            session(['2fa_verified' => true]);
            return redirect()->route('dashboard.index');
        }

        return back()->withErrors(['otp' => 'Kode tidak valid']);
    }
}
