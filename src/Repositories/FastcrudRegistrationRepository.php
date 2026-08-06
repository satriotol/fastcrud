<?php

namespace Satriotol\Fastcrud\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Satriotol\Fastcrud\Exceptions\FastcrudException;
use Satriotol\Fastcrud\Models\FastcrudRegistration;

class FastcrudRegistrationRepository
{
    public function __construct() {}

    public function getAll(array $params = [], $request = null)
    {
        $query = FastcrudRegistration::query()->with('processor');

        if ($request) {
            if ($request->name) {
                $query->where('name', 'LIKE', '%' . $request->name . '%');
            }
            if ($request->email) {
                $query->where('email', 'LIKE', '%' . $request->email . '%');
            }
            if ($request->status) {
                $query->where('status', $request->status);
            }
        }

        return $query;
    }

    public function findByUuid(string $uuid)
    {
        return FastcrudRegistration::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Aturan validasi form pendaftaran publik.
     * Aturan password mengikuti FastcrudUserRepository::validate().
     */
    public function validateRegister()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                Rule::unique('fastcrud_registrations', 'email')->where('status', 'pending'),
            ],
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
            'note' => 'nullable|string|max:500',
        ];
        $messages = [
            'email.unique' => 'E-mail ini sudah terdaftar atau sedang menunggu persetujuan.',
            'password.required' => 'Password harus diisi.',
            'password.string' => 'Password harus berupa string.',
            'password.min' => 'Password harus minimal 8 karakter.',
            'password.confirmed' => 'Password konfirmasi tidak cocok.',
            'password.regex' => 'Password harus mengandung huruf kecil, huruf besar, angka, dan simbol khusus.',
        ];

        return [
            'rules' => $rules,
            'messages' => $messages,
        ];
    }

    public function create(array $data)
    {
        return FastcrudRegistration::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'note' => $data['note'] ?? null,
            'status' => 'pending',
        ]);
    }

    /**
     * Setujui pendaftaran: buat user + assign role, tandai antrian sebagai approved.
     */
    public function approve(string $uuid, array $roles)
    {
        $registration = $this->findByUuid($uuid);

        if ($registration->status !== 'pending') {
            throw new FastcrudException('Pendaftaran ini sudah diproses.');
        }

        if (User::where('email', $registration->email)->exists()) {
            throw new FastcrudException('E-mail ini sudah dipakai pengguna lain.');
        }

        $user = User::create([
            'name' => $registration->name,
            'email' => $registration->email,
            // sudah ter-hash di tabel antrian, cast 'hashed' pada User tidak akan meng-hash ulang
            'password' => $registration->getRawOriginal('password'),
        ]);
        $user->assignRole($roles);

        $registration->update([
            'status' => 'approved',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return $user;
    }

    public function reject(string $uuid, ?string $reason = null)
    {
        $registration = $this->findByUuid($uuid);

        if ($registration->status !== 'pending') {
            throw new FastcrudException('Pendaftaran ini sudah diproses.');
        }

        $registration->update([
            'status' => 'rejected',
            'reject_reason' => $reason,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return $registration;
    }

    public function delete(string $uuid)
    {
        $this->findByUuid($uuid)->delete();
    }
}
