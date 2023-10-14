<?php

namespace App\Http\Controllers;

use App\Traits\CrudFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CrudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use CrudFunction;
    public function index()
    {
        return view('backend.crud.index');
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
            'model' => 'required',
            'singular' => 'required',
            'table' => 'required',
            'columns.*' => 'required',
            'sidebarLogo' => 'required',
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
        return back();
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
