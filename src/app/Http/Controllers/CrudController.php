<?php

namespace App\Http\Controllers;

use Satriotol\Fastcrud\Traits\CrudFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CrudController extends Controller
{
    use CrudFunction;

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

        return view('backend.crud.create', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'model' => 'required|string',
            'singular' => 'required|string',
            'table' => 'required|string',
            'columns' => 'required|array',
            'columns.*.column_name' => 'required|string',
            'columns.*.column_name_view' => 'required|string',
            'columns.*.type' => 'required|string|in:string,integer,longText,unsignedBigInteger,boolean,date',
            'columns.*.nullable' => 'required|boolean',
            'columns.*.is_file' => 'required|boolean',
            'sidebarLogo' => 'required|string',
        ]);

        $data['plural'] = Str::plural($data['singular']);

        try {
            $this->createMigration($data);
            $this->generateModel($data);
            $this->generateController($data);
            $this->addRoute($data);
            $this->generateSidebar($data);
            $this->viewIndex($data);
            $this->viewCreate($data);
            $this->storePermission($data);
            session()->flash('success', 'CRUD Berhasil Dibuat');
        } catch (\Exception $e) {
            // Log error message and flash error to the session
            \Log::error('CRUD Creation Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat membuat CRUD');
        }

        return redirect()->route('crud.index');
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
