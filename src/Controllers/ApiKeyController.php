<?php

namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Satriotol\Fastcrud\Models\ApiKey;

class ApiKeyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:api_key-index|api_key-create|api_key-edit|api_key-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:api_key-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:api_key-edit', ['only' => ['edit', 'update', 'toggle']]);
        $this->middleware('permission:api_key-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $api_keys = ApiKey::latest()->paginate();
        $request->flash();
        return view('fastcrud::api_key.index', compact('api_keys'));
    }

    public function create()
    {
        return view('fastcrud::api_key.create');
    }

    public function store(Request $request)
    {
        $key = bin2hex(random_bytes(32));
        $data = $request->validate([
            'note' => 'nullable',
            'allow_web' => 'boolean',
            'allow_mobile' => 'boolean',
            'allow_flutter' => 'boolean',
            'allow_desktop' => 'boolean',
            'allow_backend' => 'boolean',
            'allow_localhost' => 'boolean',
            'allowed_routes_text' => 'nullable|string',
        ]);

        foreach (['allow_web', 'allow_mobile', 'allow_flutter', 'allow_desktop', 'allow_backend', 'allow_localhost'] as $field) {
            $data[$field] = $request->boolean($field);
        }

        $routes = array_filter(array_map('trim', explode("\n", $request->input('allowed_routes_text', ''))));
        $data['allowed_routes'] = count($routes) ? array_values($routes) : null;
        unset($data['allowed_routes_text']);

        $data['key'] = $key;
        $data['is_active'] = true;
        $data['note'] = $request->note ?? '';

        ApiKey::create($data);
        return redirect(route('api_key.index'))->with('success', 'ApiKey Berhasil Tersimpan');
    }

    public function edit($uuid)
    {
        $api_key = ApiKey::where('uuid', $uuid)->firstOrFail();
        return view('fastcrud::api_key.create', compact('api_key'));
    }

    public function update(Request $request, $uuid)
    {
        $api_key = ApiKey::where('uuid', $uuid)->firstOrFail();
        $data = $request->validate([
            'last_used_at' => 'nullable',
            'note' => 'nullable',
            'allow_web' => 'boolean',
            'allow_mobile' => 'boolean',
            'allow_flutter' => 'boolean',
            'allow_desktop' => 'boolean',
            'allow_backend' => 'boolean',
            'allow_localhost' => 'boolean',
            'allowed_routes_text' => 'nullable|string',
        ]);

        foreach (['allow_web', 'allow_mobile', 'allow_flutter', 'allow_desktop', 'allow_backend', 'allow_localhost'] as $field) {
            $data[$field] = $request->boolean($field);
        }

        $routes = array_filter(array_map('trim', explode("\n", $request->input('allowed_routes_text', ''))));
        $data['allowed_routes'] = count($routes) ? array_values($routes) : null;
        unset($data['allowed_routes_text']);

        // Handle boolean fields
        $data['is_active'] = $request->has('is_active');

        $api_key->update($data);
        return redirect(route('api_key.index'))->with('success', 'ApiKey Berhasil Terupdate');
    }

    public function logs($uuid)
    {
        $api_key = ApiKey::where('uuid', $uuid)->firstOrFail();
        $logs = $api_key->logs()->latest()->paginate(50);
        return view('fastcrud::api_key.logs', compact('api_key', 'logs'));
    }

    public function toggle($uuid)
    {
        $api_key = ApiKey::where('uuid', $uuid)->firstOrFail();
        $api_key->update(['is_active' => !$api_key->is_active]);
        return back()->with('success', 'Status ApiKey Berhasil Diubah');
    }

    public function destroy($uuid)
    {
        $api_key = ApiKey::where('uuid', $uuid)->firstOrFail();
        $api_key->delete();
        return back()->with('success', 'ApiKey Berhasil Dihapus');
    }
}
