<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Config;
use Illuminate\Support\Str;
use Satriotol\Fastcrud\Traits\RemovesFiles;

class ConfigController extends Controller
{
    use RemovesFiles;
    public function __construct()
    {
        $this->middleware('permission:config-index|config-create|config-edit|config-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:config-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:config-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:config-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $configs = Config::latest()->paginate();
        $request->flash();
        return view('backend.config.index', compact('configs'));
    }

    public function create()
    {
        $types = Config::types();
        return view('backend.config.create', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required',
            'type' => 'required',
            'config_value' => 'required',
        ]);

        // Handle file uploads




        // Handle boolean fields


        Config::create($data);
        return redirect(route('config.index'))->with('success', 'Config Berhasil Dibuat');
    }

    public function edit($uuid)
    {
        $config = Config::where('uuid', $uuid)->firstOrFail();
        $types = Config::types();
        return view('backend.config.create', compact('config', 'types'));
    }

    public function update(Request $request, $uuid)
    {
        $config = Config::where('uuid', $uuid)->firstOrFail();
        $data = $request->validate([
            'description' => 'required',
            'type' => 'required',
            'config_value' => 'required',
        ]);

        // Handle file uploads




        // Handle boolean fields


        $config->update($data);
        return redirect(route('config.index'))->with('success', 'Config Berhasil Terupdate');
    }

    public function destroy($uuid)
    {
        $config = Config::where('uuid', $uuid)->firstOrFail();
        $config->delete();
        return back()->with('success', 'Config Berhasil Dihapus');
    }
}
