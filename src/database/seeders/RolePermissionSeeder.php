<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $json = file_get_contents(base_path('roles.json'));
        $data = json_decode($json, true);

        // Create permissions
        foreach ($data['permissions'] as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create/Sync role permissions
        foreach ($data['roles'] as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($permissions);
        }
    }
}
