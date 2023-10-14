<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::latest()->paginate();
        return view('backend.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();

        // Mengelompokkan permission berdasarkan prefix
        $permissionsGrouped = $permissions->groupBy(function ($permission) {
            return explode('-', $permission->name)[0];
        });

        return view('backend.role.create', compact('permissionsGrouped'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'permissions' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permissions')); // Ubah 'permission' menjadi 'permissions'

        session()->flash('success', 'Role berhasil disimpan.'); // Tambahkan pesan sukses
        return redirect()->route('role.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();

        // Mengelompokkan permission berdasarkan prefix
        $permissionsGrouped = $permissions->groupBy(function ($permission) {
            return explode('-', $permission->name)[0];
        });

        return view('backend.role.create', compact('permissionsGrouped', 'role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required',
            'permissions' => 'required',
        ]);
        $role->update(['name' => $data['name']]);

        $role->syncPermissions($request->input('permissions')); // Ubah 'permission' menjadi 'permissions'

        session()->flash('success', 'Role berhasil diubah.'); // Tambahkan pesan sukses
        return redirect()->route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        session()->flash('success','Role berhasil dihapus');
        return back();
    }
}
