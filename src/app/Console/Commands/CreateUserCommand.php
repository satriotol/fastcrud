<?php

namespace Satriotol\Fastcrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateUserCommand extends Command
{
    protected $signature = 'fastcrud:create-user';
    protected $description = 'Create a new user';

    public function handle()
    {
        // Ask for user input
        $name = $this->ask('Masukkan Nama Anda');
        $email = $this->ask('Masukkan Alamat Email Anda');
        $password = $this->secret('Masukkan Password Anda');
        $password_confirmation = $this->secret('Masukkan Ulang Password Anda');

        // Validate input
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
            'role' => 'SUPERADMIN',
        ];

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            $this->info('Error in user creation:');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return;
        }

        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        
        // Assign role to user
        $user->assignRole($data['role']);

        $this->info('User created successfully!');
    }
}
