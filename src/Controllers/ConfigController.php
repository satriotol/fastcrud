<?php

namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Satriotol\Fastcrud\Models\Config;
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
        $description = $request->description;
        $config_value = $request->config_value;
        $configs = Config::query();
        if ($description) {
            $configs = $configs->where('description', 'like', "%$description%");
        }
        if ($config_value) {
            $configs = $configs->where('config_value', 'like', "%$config_value%");
        }
        $configs = $configs->latest()->paginate(5);
        $request->flash();
        return view('fastcrud::config.index', compact('configs'));
    }

    public function create()
    {
        $types = Config::types();
        return view('fastcrud::config.create', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'config_key' => 'nullable|string|max:191|unique:configs,config_key',
            'description' => 'required',
            'type' => 'required|in:' . implode(',', Config::types()),
            'config_value' => 'required',
        ]);

        // Handle file uploads




        // Handle boolean fields


        if (Config::isFileTypeValue($data['type'])) {
            $request->validate(['config_value' => 'required|file|max:10240']);
            $data['config_value'] = $this->storeAsset($request->file('config_value'));
        }

        Config::create($data);
        Config::flushCache();
        return redirect(route('config.index'))->with('success', 'Config Berhasil Dibuat');
    }
    public function getConfig($uuid)
    {
        $config = Config::where('uuid', $uuid)->firstOrFail();

        if ($config->type == 'url') {
            return redirect($config->config_value);
        } elseif ($config->isFileType()) {
            return redirect($config->asset_url);
        } else {
            return response($config->config_value);  // Tampilkan nilai config_value sebagai teks
        }
    }

    public function edit($uuid)
    {
        $config = Config::where('uuid', $uuid)->firstOrFail();
        $types = Config::types();
        return view('fastcrud::config.create', compact('config', 'types'));
    }

    public function update(Request $request, $uuid)
    {
        $config = Config::where('uuid', $uuid)->firstOrFail();
        $data = $request->validate([
            'config_key' => 'nullable|string|max:191|unique:configs,config_key,' . $config->id,
            'description' => 'required',
            'type' => 'required|in:' . implode(',', Config::types()),
            'config_value' => Config::isFileTypeValue($request->input('type')) ? 'nullable|file|max:10240' : 'required',
        ]);

        // Handle file uploads




        // Handle boolean fields


        if (Config::isFileTypeValue($data['type'])) {
            $data['config_value'] = $request->hasFile('config_value')
                ? $this->storeAsset($request->file('config_value'))
                : $config->config_value;
        }

        $config->update($data);
        Config::flushCache();
        return redirect(route('config.index'))->with('success', 'Config Berhasil Terupdate');
    }

    public function destroy($uuid)
    {
        $config = Config::where('uuid', $uuid)->firstOrFail();
        $config->delete();
        Config::flushCache();
        return back()->with('success', 'Config Berhasil Dihapus');
    }

    public function clearCache()
    {
        Config::flushCache();
        return back()->with('success', 'Cache Config Berhasil Dibersihkan');
    }

    private function storeAsset($file): string
    {
        return $file->store('config', config('fastcrud.asset_disk', 'public'));
    }
}
