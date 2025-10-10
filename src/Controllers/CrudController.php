<?php

namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
use Satriotol\Fastcrud\Traits\CrudFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CrudController extends Controller
{
    use CrudFunction;
    public function __construct()
    {
        if (!config('app.debug')) {
            abort(403, 'Access denied');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('crud.create');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $type = [
            'string' => "string",
            'integer' => 'integer',
            'longText' => 'longText',
            'unsignedBigInteger' => 'unsignedBigInteger',
            'boolean' => 'boolean',
            'date' => 'date'
        ];

        return view('fastcrud::crud.create', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'model' => ['required', 'string', 'max:64', 'alpha_dash'],
            'singular' => ['required', 'string', 'max:64', 'alpha_dash'],
            'table' => ['required', 'string', 'max:64', 'alpha_dash'],
            'indonesian_name' => ['required', 'string', 'max:64'],
            'indonesian_description' => ['required', 'string', 'max:255'],
            'columns' => ['required', 'array', 'min:1'],
            'columns.*.column_name' => ['required', 'string', 'max:64', 'alpha_dash'],
            'columns.*.column_name_view' => ['required', 'string', 'max:64'],
            'columns.*.type' => ['required', 'string'],
            'columns.*.nullable' => ['required', 'boolean'],
            'columns.*.is_file' => ['required', 'boolean'],
            'columns.*.is_minio' => ['required', 'boolean'],
            'sidebarLogo' => ['required', 'string', 'max:64'],
        ], [
            'model.required' => 'Nama model wajib diisi.',
            'model.alpha_dash' => 'Nama model hanya boleh huruf, angka, tanda hubung atau underscore.',
            'model.max' => 'Nama model maksimal 64 karakter.',
            'singular.required' => 'Nama singular wajib diisi.',
            'singular.max' => 'Nama singular maksimal 64 karakter.',
            'table.required' => 'Nama tabel wajib diisi.',
            'table.max' => 'Nama tabel maksimal 64 karakter.',
            'columns.required' => 'Kolom wajib diisi minimal satu.',
            'columns.*.column_name.required' => 'Nama kolom wajib diisi.',
            'columns.*.column_name.max' => 'Nama kolom maksimal 64 karakter.',
            'columns.*.column_name_view.required' => 'Label kolom wajib diisi.',
            'columns.*.column_name_view.max' => 'Label kolom maksimal 64 karakter.',
            'columns.*.type.in' => 'Tipe kolom tidak valid.',
            'columns.*.nullable.boolean' => 'Nilai nullable harus berupa true/false.',
            'columns.*.is_file.boolean' => 'Nilai is_file harus berupa true/false.',
            'columns.*.is_minio.boolean' => 'Nilai is_minio harus berupa true/false.',
            'sidebarLogo.required' => 'Icon sidebar wajib diisi.',
            'sidebarLogo.max' => 'Nama icon sidebar maksimal 64 karakter.',

        ]);

        $data['plural'] = Str::plural($data['singular']);

        $this->createMigration($data);
        $this->generateModel($data);
        $this->generateController($data);
        $this->addRoute($data);
        $this->generateSidebar($data);
        $this->viewIndex($data);
        $this->viewCreate($data);
        $this->storePermission($data);
        $this->generateRepository($data);

        return back()->with('success', 'CRUD Berhasil Dibuat, silahkan jalankan command <code>php artisan migrate</code> untuk membuat tabel baru.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Implement logic to show a resource
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Implement logic to show the edit form
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Implement logic to update a resource
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Implement logic to delete a resource
    }
}
