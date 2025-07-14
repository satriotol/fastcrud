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
use Satriotol\Fastcrud\Repositories\FastcrudPasswordHistoryRepository;
use Satriotol\Fastcrud\Repositories\FastcrudPasswordRepository;
use Satriotol\Fastcrud\Repositories\FastcrudUserRepository;

class PasswordController extends Controller
{
    protected $fastcrudUserRepository;
    protected $fastcrudPasswordHistoryRepository;
    protected $fastcrudPasswordRepository;

    public function __construct()
    {
        $this->fastcrudUserRepository = new FastcrudUserRepository();
        $this->fastcrudPasswordHistoryRepository = new FastcrudPasswordHistoryRepository();
        $this->fastcrudPasswordRepository = new FastcrudPasswordRepository();
        $this->middleware('permission:fastcrud_user_reset_password-single', ['only' => ['resetPassword']]);
        $this->middleware('permission:fastcrud_user_reset_password-multiple', ['only' => ['resetPasswords']]);
    }
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
        $user = $this->fastcrudUserRepository->findByUuid($uuid);

        $fastcrudPasswordHistory = $this->fastcrudPasswordHistoryRepository->create([
            'user_id' => $user->id,
            'password' => $user->password
        ]);
        $newPassword = substr(bin2hex(random_bytes(10)), 0, 10);

        $user->password = Hash::make($newPassword);
        $user->must_change_password = true;
        $user->save();

        return redirect()->back()->with('success', "Password telah direset. Password baru: $newPassword");
    }

    public function resetPasswords(Request $request, $users = null)
    {
        if (!$users) {
            $data = $request->validate([
                'role' => 'required'
            ]);
            $users = $this->fastcrudUserRepository->getAll([], $request)
                ->whereHas('roles', function ($query) use ($data) {
                    $query->where('name', $data['role']);
                })
                ->get();
        }

        $passwords = [];

        foreach ($users as $user) {
            $fastcrudPasswordHistory = $this->fastcrudPasswordHistoryRepository->create([
                'user_id' => $user->id,
                'password' => $user->password
            ]);
            $newPassword = Str::random(8); // Generate a random password
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
        $request->validate($this->fastcrudPasswordRepository->validate()['rules'], $this->fastcrudPasswordRepository->validate()['messages']);

        $user = auth()->user();
        $newPassword = $request->password;
        $historyPasswords = $this->fastcrudPasswordHistoryRepository->getAll([], null)->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        foreach ($historyPasswords as $history) {
            if (Hash::check($newPassword, $history->password)) {
                return back()->withErrors('Password baru tidak boleh sama dengan 5 password terakhir.');
            }
        }
        $data = [
            'user_id' => $user->id,
            'password' => $user->password,
        ];
        $this->fastcrudPasswordHistoryRepository->create($data);
        $historyCount = $this->fastcrudPasswordHistoryRepository->getAll([], null)->where('user_id', $user->id)->count();
        if ($historyCount > 5) {
            $this->fastcrudPasswordHistoryRepository->getAll([], null)
                ->where('user_id', $user->id)
                ->orderBy('created_at')
                ->limit($historyCount - 5)
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
