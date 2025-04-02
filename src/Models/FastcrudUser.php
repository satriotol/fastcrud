<?php

namespace Satriotol\Fastcrud\Models;

class FastcrudUser extends User
{
    protected $table = 'users'; // Menggunakan tabel yang sama

    protected $fillable =
    [
        'last_password_change',
        'google2fa_secret',
        'google2fa_verified'
    ];


    public function generateGoogle2FASecret()
    {
        $google2fa = app('pragmarx.google2fa');
        $this->google2fa_secret = $google2fa->generateSecretKey();
        $this->save();
    }
}
