<?php

namespace Satriotol\Fastcrud\Repositories;

use Satriotol\Fastcrud\Traits\RemovesFiles;

class FastcrudPasswordRepository
{
    use RemovesFiles;

    public function validate($isUpdate = false)
    {
        $rules = [
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&_]/',
            ],
        ];
        if ($isUpdate) {
        }
        $messages = [
            'password.required' => 'Kata sandi wajib diisi.',
            'password.string' => 'Kata sandi harus berupa teks.',
            'password.min' => 'Kata sandi minimal harus terdiri dari :min karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.regex' => 'Kata sandi harus mengandung huruf besar, huruf kecil, angka, dan simbol.',
            'password.regex:/[a-z]/' => 'Kata sandi harus mengandung setidaknya satu huruf kecil.',
            'password.regex:/[A-Z]/' => 'Kata sandi harus mengandung setidaknya satu huruf besar.',
            'password.regex:/[0-9]/' => 'Kata sandi harus mengandung setidaknya satu angka.',
            'password.regex:/[@$!%*#?&_]/' => 'Kata sandi harus mengandung setidaknya satu karakter spesial (@$!%*#?&_).',
        ];
        return ['rules' => $rules, 'messages' => $messages];
    }
}
