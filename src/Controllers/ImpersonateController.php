<?php

namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    /**
     * Mulai "melihat sebagai" user lain.
     * Hanya SUPERADMIN asli yang boleh memulai (mencegah impersonasi berlapis).
     */
    public function start(User $user)
    {
        $current = Auth::user();

        abort_unless($current && $current->hasRole('SUPERADMIN'), 403, 'Hanya superadmin yang dapat menggunakan fitur ini.');

        if ($current->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat melihat sebagai diri sendiri.');
        }

        if ($user->hasRole('SUPERADMIN')) {
            return back()->with('error', 'Tidak dapat melihat sebagai sesama superadmin.');
        }

        // Ingat siapa superadmin aslinya, lalu login sebagai target.
        session(['impersonator_id' => $current->id]);
        Auth::login($user);

        return redirect()->route('dashboard.index')
            ->with('success', 'Anda sekarang melihat sebagai ' . $user->name . '.');
    }

    /**
     * Kembali ke akun superadmin asli.
     */
    public function stop()
    {
        $impersonatorId = session('impersonator_id');

        abort_unless($impersonatorId, 403, 'Anda tidak sedang dalam mode impersonasi.');

        Auth::loginUsingId($impersonatorId);
        session()->forget('impersonator_id');

        return redirect()->route('fastcrud_user.index')
            ->with('success', 'Anda telah kembali ke akun superadmin.');
    }
}
