<?php

namespace Satriotol\Fastcrud\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Satriotol\Fastcrud\Traits\RemovesFiles;
use Illuminate\Support\Facades\Hash;

class FastcrudUserRepository
{

    public function __construct() {}
    use RemovesFiles;

    public function getAll(array $params = [], $request)
    {
        if (Auth::user()->hasRole('SUPERADMIN')) {
            $query = User::query();
        } else {
            $query = User::whereHas('roles', function ($query) {
                $query->whereNot('name', 'SUPERADMIN');
            });
        }
        $name = $request->name;
        $must_change_password = $request->must_change_password;
        $role = $request->role;
        if ($name) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }
        if (isset($must_change_password)) {
            $query->where('must_change_password', (bool) $must_change_password);
        }
        if ($role) {
            $query->whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            });
        }

        return $query;
    }

    public function findByUuid(string $uuid)
    {
        return User::where('uuid', $uuid)->firstOrFail();
    }
    public function validate()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
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
            'role' => 'required',
        ];
        $messages = [
            'password.required' => 'Password harus diisi.',
            'password.string' => 'Password harus berupa string.',
            'password.min' => 'Password harus minimal 8 karakter.',
            'password.confirmed' => 'Password konfirmasi tidak cocok.',
            'password.regex' => 'Password harus mengandung huruf kecil, huruf besar, angka, dan simbol khusus.',
        ];
        return [
            'rules' => $rules,
            'messages' => $messages
        ];
    }

    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $user->assignRole($data['role']);
        return $user;
    }
    public function updateOrCreate(array $data)
    {
        $user = User::updateOrCreate(
            [
                'email' => $data['email']
            ],
            $data
        );
        $user->syncRoles($data['role']);
        return $user;
    }

    public function update(string $uuid, array $data)
    {
        $model = $this->findByUuid($uuid);



        $model->update($data);
        return $model;
    }

    public function delete(string $uuid)
    {
        $model = $this->findByUuid($uuid);



        $model->delete();
    }
    public function setPhoneNumber(array $data)
    {
        $user = Auth::user();
        $user->update([
            'phone' => $data['phone']
        ]);
        return $user;
    }

    private function uploadImage($file, $name)
    {
        $envName = env('APP_NAME', 'default');
        $path = $envName . '/operational_category/' . $name;

        return Storage::disk('minio')->put($path, $file);
    }
}
