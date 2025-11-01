<?php

namespace Satriotol\Fastcrud\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ExportRolePermission extends Command
{
    protected $signature = 'fastcrud:role-export {file=roles.json}';
    protected $description = 'Export roles & permissions ke file JSON';

    public function handle()
    {
        $permissions = Permission::all()->pluck('name');

        $roles = Role::with('permissions')->get()
            ->mapWithKeys(function ($role) {
                return [
                    $role->name => $role->permissions->pluck('name')->toArray()
                ];
            });

        $data = [
            'permissions' => $permissions,
            'roles' => $roles,
        ];

        file_put_contents(
            base_path($this->argument('file')),
            json_encode($data, JSON_PRETTY_PRINT)
        );

        $this->info('✅ Role & permission berhasil diexport ke: ' . $this->argument('file'));
    }
}
