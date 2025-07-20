<?php

namespace Satriotol\Fastcrud\Repositories;

use Illuminate\Support\Facades\Auth;
use Satriotol\Fastcrud\Traits\RemovesFiles;
use Spatie\Permission\Models\Role;

class FastcrudRoleRepository
{

    public function __construct() {}
    use RemovesFiles;

    public function getAll(array $params = [], $request)
    {
        if (Auth::user()->getRole()->name == 'SUPERADMIN') {
            $query = Role::query();
        } else {
            $query = Role::where('name', '!=', 'SUPERADMIN');
        }
        $name = $request->name;
        if ($name) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        return $query;
    }
    public function search(){
        $query = Role::orderBy('name');
        if (Auth::user()->name != 'SUPERADMIN') {
            $query->where('name', '!=', 'SUPERADMIN');
        }
        return $query;

    }
}
