<?php

namespace Satriotol\Fastcrud\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionManagerController extends Controller
{
    /**
     * Daftar semua role beserta jumlah permission-nya.
     */
    public function index()
    {
        $roles = Role::withCount('permissions')->orderBy('name')->get();

        return view('backend.permission_manager.index', compact('roles'));
    }

    /**
     * Matrix permission untuk satu role, dikelompokkan per modul.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get()
            ->groupBy(fn ($permission) => Str::beforeLast($permission->name, '-'));

        $assigned = $role->permissions->pluck('name')->all();

        return view('backend.permission_manager.edit', compact('role', 'permissions', 'assigned'));
    }

    /**
     * Simpan perubahan permission role. Berlaku langsung setelah cache dibersihkan.
     */
    public function update(Request $request, Role $role)
    {
        abort_if($role->name === 'SUPERADMIN', 403, 'Permission SUPERADMIN tidak dapat diubah.');

        $names = $request->input('permissions', []);

        // Pastikan hanya permission yang benar-benar ada yang di-sync.
        $valid = Permission::whereIn('name', $names)->pluck('name')->all();

        $role->syncPermissions($valid);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->back()
            ->with('success', "Permission untuk role \"{$role->name}\" berhasil diperbarui.");
    }
}
