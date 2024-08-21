<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ApiKey;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:api_key-index|api_key-create|api_key-edit|api_key-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:api_key-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:api_key-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:api_key-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $api_keys = ApiKey::latest()->paginate();
        $request->flash();
        return view('backend.api_key.index', compact('api_keys'));
    }

    public function create()
    {
        return view('backend.api_key.create');
    }

    public function store(Request $request)
    {
        $key = bin2hex(random_bytes(32));
        ApiKey::create([
            'key' => $key,
            'is_active' => true,
        ]);
        return redirect(route('api_key.index'))->with('success', 'ApiKey Berhasil Tersimpan');
    }

    public function edit($uuid)
    {
        $api_key = ApiKey::where('uuid', $uuid)->firstOrFail();
        return view('backend.api_key.create', compact('api_key'));
    }

    public function update(Request $request, $uuid)
    {
        $api_key = ApiKey::where('uuid', $uuid)->firstOrFail();
        $data = $request->validate([
            'key' => 'required',
            'is_active' => 'required',
            'last_used_at' => 'nullable',
        ]);

        // Handle file uploads




        // Handle boolean fields
        $data['is_active'] = $request->has('is_active');

        $api_key->update($data);
        return redirect(route('api_key.index'))->with('success', 'ApiKey Berhasil Terupdate');
    }

    public function destroy($uuid)
    {
        $api_key = ApiKey::where('uuid', $uuid)->firstOrFail();
        $api_key->delete();
        return back()->with('success', 'ApiKey Berhasil Dihapus');
    }
}
