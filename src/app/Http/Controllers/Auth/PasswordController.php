<?php

namespace App\Http\Controllers\Auth;

use Satriotol\Fastcrud\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
    public function resetPassword($uuid)
    {
        $user = User::where('uuid', $uuid)->first();
        $newPassword = Str::random(8); // Menghasilkan password baru
        $user->password = Hash::make($newPassword);
        $user->must_change_password = true;
        $user->save();

        // Mengirim email atau notifikasi berisi password baru
        // Mail::to($user->email)->send(new ResetPasswordMail($newPassword));

        return redirect()->back()->with('success', "Password telah direset. Password baru: $newPassword");
    }
    public function resetPasswords()
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'SUPERADMIN');
        })->get();

        $passwords = [];

        foreach ($users as $user) {
            $newPassword = Str::random(24); // Generate a random password
            $user->password = Hash::make($newPassword);
            $user->save();

            $passwords[] = [
                'email' => $user->email,
                'password' => $newPassword,
            ];
        }

        // Export to Excel
        return Excel::download(new UsersExport($passwords), 'users_passwords.xlsx');
    }

    public function showChangePasswordForm()
    {
        return view('backend.user.resetPassword');
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/', // Mengandung huruf kecil
                'regex:/[A-Z]/', // Mengandung huruf besar
                'regex:/[0-9]/', // Mengandung angka
                'regex:/[@$!%*#?&_]/', // Mengandung simbol khusus
            ],
        ], [
            'password.required' => 'Password harus diisi.',
            'password.string' => 'Password harus berupa string.',
            'password.min' => 'Password harus minimal 8 karakter.',
            'password.confirmed' => 'Password konfirmasi tidak cocok.',
            'password.regex' => 'Password harus mengandung huruf kecil, huruf besar, angka, dan simbol khusus.',
        ]);

        $user = auth()->user();

        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['new_password' => 'Password baru tidak boleh sama dengan password lama.']);
        }

        // Simpan password baru
        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        return redirect()->route('login');
    }
}
