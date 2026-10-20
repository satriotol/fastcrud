<?php

namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
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

        return view('fastcrud::permission_manager.index', compact('roles'));
    }

    /**
     * Matrix permission untuk satu role, dikelompokkan per modul.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get()
            ->groupBy(fn ($permission) => Str::beforeLast($permission->name, '-'));

        $assigned = $role->permissions->pluck('name')->all();

        return view('fastcrud::permission_manager.edit', compact('role', 'permissions', 'assigned'));
    }

    /**
     * Buat permission dari JSON yang belum tersedia di environment ini.
     */
    public function createMissingPermissions(Request $request, Role $role)
    {
        $data = $request->validate([
            'permissions' => ['required', 'array', 'max:200'],
            'permissions.*' => ['required', 'string', 'distinct', 'max:255'],
        ]);

        foreach ($data['permissions'] as $name) {
            Permission::firstOrCreate([
                'name' => trim($name),
                'guard_name' => $role->guard_name,
            ]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->back()->with(
            'success',
            count($data['permissions']) . ' permission yang belum tersedia berhasil dibuat.'
        );
    }

    /**
     * Simpan perubahan permission role. Berlaku langsung setelah cache dibersihkan.
     */
    public function update(Request $request, Role $role)
    {
        $names = $request->input('permissions', []);

        // Pastikan hanya permission yang benar-benar ada yang di-sync.
        $valid = Permission::whereIn('name', $names)->pluck('name')->all();

        $role->syncPermissions($valid);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->back()
            ->with('success', "Permission untuk role \"{$role->name}\" berhasil diperbarui.");
    }
}
