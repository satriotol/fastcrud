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
            'allowed_ips_text' => 'nullable|string',
        ]);

        foreach (['allow_web', 'allow_mobile', 'allow_flutter', 'allow_desktop', 'allow_backend', 'allow_localhost'] as $field) {
            $data[$field] = $request->boolean($field);
        }

        $routes = array_filter(array_map('trim', explode("\n", $request->input('allowed_routes_text', ''))));
        $data['allowed_routes'] = count($routes) ? array_values($routes) : null;
        unset($data['allowed_routes_text']);

        $ips = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $request->input('allowed_ips_text', ''))));
        $data['allowed_ips'] = count($ips) ? array_values($ips) : null;
        unset($data['allowed_ips_text']);

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
            'allowed_ips_text' => 'nullable|string',
        ]);

        foreach (['allow_web', 'allow_mobile', 'allow_flutter', 'allow_desktop', 'allow_backend', 'allow_localhost'] as $field) {
            $data[$field] = $request->boolean($field);
        }

        $routes = array_filter(array_map('trim', explode("\n", $request->input('allowed_routes_text', ''))));
        $data['allowed_routes'] = count($routes) ? array_values($routes) : null;
        unset($data['allowed_routes_text']);

        $ips = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $request->input('allowed_ips_text', ''))));
        $data['allowed_ips'] = count($ips) ? array_values($ips) : null;
        unset($data['allowed_ips_text']);

        // Handle boolean fields
        $data['is_active'] = $request->has('is_active');

        $api_key->update($data);
        return redirect(route('api_key.index'))->with('success', 'ApiKey Berhasil Terupdate');
    }

    public function logs(Request $request, $uuid)
    {
        $api_key = ApiKey::where('uuid', $uuid)->firstOrFail();

        $filter = $request->input('filter', 'all'); // all|24h|7d|30d
        $base = $api_key->logs();
        $scoped = match ($filter) {
            '24h' => $api_key->logs()->where('created_at', '>=', now()->subDay()),
            '7d'  => $api_key->logs()->where('created_at', '>=', now()->subDays(7)),
            '30d' => $api_key->logs()->where('created_at', '>=', now()->subDays(30)),
            default => $api_key->logs(),
        };

        $stats = [
            'total'        => (clone $scoped)->count(),
            'success'      => (clone $scoped)->whereBetween('status_code', [200, 299])->count(),
            'client_error' => (clone $scoped)->whereBetween('status_code', [400, 499])->count(),
            'server_error' => (clone $scoped)->where('status_code', '>=', 500)->count(),
            'unique_ips'   => (clone $scoped)->distinct('ip_address')->count('ip_address'),
            'last_hit'     => (clone $base)->max('created_at'),
        ];
        $stats['error_rate'] = $stats['total'] > 0
            ? round(($stats['client_error'] + $stats['server_error']) / $stats['total'] * 100, 1)
            : 0.0;
        $activeDays = (clone $scoped)->selectRaw('count(distinct DATE(created_at)) as c')->value('c') ?: 1;
        $stats['avg_per_day'] = round($stats['total'] / $activeDays, 1);

        $topEndpoints = (clone $scoped)->selectRaw('endpoint, count(*) as hits, avg(status_code) as avg_status')
            ->groupBy('endpoint')->orderByDesc('hits')->limit(10)->get();

        $byMethod = (clone $scoped)->selectRaw('method, count(*) as hits')
            ->groupBy('method')->orderByDesc('hits')->get();

        $byClient = (clone $scoped)->selectRaw("coalesce(client_type,'unknown') as client_type, count(*) as hits")
            ->groupBy('client_type')->orderByDesc('hits')->get();

        $byStatus = (clone $scoped)->selectRaw("coalesce(status_code,0) as status_code, count(*) as hits")
            ->groupBy('status_code')->orderByDesc('hits')->get();

        $topIps = (clone $scoped)->selectRaw("coalesce(ip_address,'-') as ip_address, count(*) as hits")
            ->groupBy('ip_address')->orderByDesc('hits')->limit(10)->get();

        // Hit per hari (maks 30 hari terakhir, urut lama→baru)
        $daily = (clone $scoped)->selectRaw('DATE(created_at) as day, count(*) as hits')
            ->groupBy('day')->orderByDesc('day')->limit(30)->get()->sortBy('day')->values();

        $logs = (clone $base)->latest()->paginate(50)->withQueryString();

        return view('fastcrud::api_key.logs', compact(
            'api_key', 'logs', 'filter', 'stats',
            'topEndpoints', 'byMethod', 'byClient', 'byStatus', 'topIps', 'daily'
        ));
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
