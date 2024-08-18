<?php

namespace Satriotol\Fastcrud\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromArray, WithHeadings
{
    protected $passwords;

    public function __construct(array $passwords)
    {
        $this->passwords = $passwords;
    }

    public function array(): array
    {
        return $this->passwords;
    }

    public function headings(): array
    {
        return [
            'Email',
            'Password',
        ];
    }
}
