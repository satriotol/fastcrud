<?php

namespace Satriotol\Fastcrud\Controllers;

use Satriotol\Fastcrud\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Satriotol\Fastcrud\Models\FastcrudPasswordHistory;

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

        // Menghasilkan password baru yang lebih aman
        $newPassword = substr(bin2hex(random_bytes(10)), 0, 10);

        $user->password = Hash::make($newPassword);
        $user->must_change_password = true;
        $user->save();

        return redirect()->back()->with('success', "Password telah direset. Password baru: $newPassword");
    }

    public function resetPasswords(Request $request)
    {
        $data = $request->validate([
            'role' => 'required'
        ]);
        $users = User::whereHas('roles', function ($query) use ($data) {
            $query->where('name', $data['role']);
        })->get();

        $passwords = [];

        foreach ($users as $user) {
            $newPassword = Str::random(24); // Generate a random password
            $user->password = Hash::make($newPassword);
            $user->must_change_password = true;
            $user->save();

            $passwords[] = [
                'email' => $user->email,
                'name' => $user->name,
                'password' => $newPassword,
            ];
        }

        // Export to Excel
        return Excel::download(new UsersExport($passwords), 'users_passwords.xlsx');
    }


    public function showChangePasswordForm()
    {
        return view('fastcrud::user.resetPassword');
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
        $newPassword = $request->password;
        $historyPasswords = FastcrudPasswordHistory::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        foreach ($historyPasswords as $history) {
            if (Hash::check($newPassword, $history->password)) {
                return back()->withErrors('Password baru tidak boleh sama dengan 3 password terakhir.');
            }
        }
        FastcrudPasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password, // simpan dalam kondisi hash
        ]);
        $historyCount = FastcrudPasswordHistory::where('user_id', $user->id)->count();
        if ($historyCount > 3) {
            FastcrudPasswordHistory::where('user_id', $user->id)
                ->orderBy('created_at')
                ->limit($historyCount - 3)
                ->delete();
        }


        // Simpan password baru
        $user->password = Hash::make($newPassword);
        $user->must_change_password = false;
        $user->last_password_change = now();
        $user->save();

        return redirect()->route('login');
    }
}
