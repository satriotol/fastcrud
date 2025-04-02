<?php

namespace Satriotol\Fastcrud\Models;

use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class FastcrudUser extends User
{
    protected $table = 'users'; // Menggunakan tabel yang sama

    protected $fillable =
    [
        'last_password_change',
        'google2fa_secret',
        'google2fa_verified'
    ];

    public function setGoogle2faSecretAttribute($value)
    {
        $this->attributes['google2fa_secret'] = Crypt::encrypt($value);
    }

    public function getGoogle2faSecretAttribute($value)
    {
        return Crypt::decrypt($value);
    }

    public function generateGoogle2FASecret()
    {
        $google2fa = app('pragmarx.google2fa');
        $this->google2fa_secret = $google2fa->generateSecretKey();
        $this->save();
    }
}
