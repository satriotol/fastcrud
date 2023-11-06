<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:permission-index|permission-create|permission-show|permission-edit|permission-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        $permissions = Permission::latest()->paginate();
        return view('backend.permission.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:permissions,name',
            'guard_name' => 'nullable',
            'isDefault' => 'nullable'
        ]);
        if ($request->isDefault) {
            $default = [
                '-index',
                '-create',
                '-edit',
                '-delete',
                '-show',
            ];
            foreach ($default as $d) {
                Permission::create([
                    'name' => $data['name'] . $d,
                    'guard_name' => 'web'
                ]);
            }
        } else {
            Permission::create([
                'name' => $data['name'],
                'guard_name' => 'web'
            ]);
        }
        session()->flash('success', 'Permission berhasil dibuat');
        return redirect(route('permission.index'));
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
    public function edit(Permission $permission)
    {
        return view('backend.permission.create', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'name' => 'required|unique:permissions,name',
            'guard_name' => 'nullable',
            'isDefault' => 'nullable'
        ]);
        $permission->update([
            'name' => $data['name']
        ]);
        session()->flash('success', 'Permission berhasil diubah');
        return redirect(route('permission.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        session()->flash('success', 'Permission berhasil dihapus');
        return back();
    }
}
