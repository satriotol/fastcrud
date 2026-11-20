@extends('layouts/layoutMaster')

@section('title', 'Detail API Key')

@section('content')
@php
    $maxEndpointHits = max(1, (int) $topEndpoints->max('hits'));
    $maxDailyHits = max(1, (int) $daily->max('hits'));
@endphp
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h4 class="mb-1">Detail API Key</h4>
        <div class="text-muted">{{ $api_key->note ?: $api_key->uuid }}</div>
    </div>
    <a href="{{ route('api_key.index') }}" class="btn btn-warning">Kembali</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><small class="text-muted d-block">API Key</small><code class="text-break">{{ $api_key->key }}</code></div>
            <div class="col-md-3"><small class="text-muted d-block">Status</small><span class="badge bg-label-{{ $api_key->is_active ? 'success' : 'danger' }}">{{ $api_key->is_active ? 'Aktif' : 'Nonaktif' }}</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Terakhir dipakai</small>{{ $stats['last_hit'] ? \Carbon\Carbon::parse($stats['last_hit'])->format('d M Y H:i:s') : '-' }}</div>
            <div class="col-md-6"><small class="text-muted d-block">Whitelist IP</small>{{ empty($api_key->allowed_ips) ? 'Semua IP' : implode(', ', $api_key->allowed_ips) }}</div>
            <div class="col-md-6"><small class="text-muted d-block">Whitelist Endpoint</small>{{ empty($api_key->allowed_routes) ? 'Semua endpoint' : implode(', ', $api_key->allowed_routes) }}</div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
        <h5 class="mb-0">Analitik Request</h5>
        <div class="btn-group btn-group-sm">
            @foreach(['all' => 'Semua', '24h' => '24 Jam', '7d' => '7 Hari', '30d' => '30 Hari'] as $value => $label)
                <a href="{{ route('api_key.logs', ['uuid' => $api_key->uuid, 'filter' => $value]) }}" class="btn btn-{{ $filter === $value ? 'primary' : 'outline-primary' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @foreach([
                ['Total Request', number_format($stats['total']), 'primary'], ['Berhasil (2xx)', number_format($stats['success']), 'success'],
                ['Client Error (4xx)', number_format($stats['client_error']), 'warning'], ['Server Error (5xx)', number_format($stats['server_error']), 'danger'],
                ['Error Rate', $stats['error_rate'].'%', 'danger'], ['IP Unik', number_format($stats['unique_ips']), 'info'],
                ['Rata-rata / Hari', number_format($stats['avg_per_day'], 1), 'secondary']
            ] as [$label, $value, $color])
                <div class="col-6 col-md-3 col-xl">
                    <div class="border rounded p-3 h-100"><small class="text-muted d-block">{{ $label }}</small><strong class="fs-4 text-{{ $color }}">{{ $value }}</strong></div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card h-100"><div class="card-header"><h5 class="mb-0">Endpoint Paling Sering Diakses</h5></div><div class="card-body">
            @forelse($topEndpoints as $endpoint)
                <div class="mb-3"><div class="d-flex justify-content-between gap-2"><code class="text-break">{{ $endpoint->endpoint }}</code><strong class="text-nowrap">{{ number_format($endpoint->hits) }} hit</strong></div><div class="progress mt-1" style="height: 7px"><div class="progress-bar" style="width: {{ $endpoint->hits / $maxEndpointHits * 100 }}%"></div></div><small class="text-muted">Rata-rata status: {{ round($endpoint->avg_status) }}</small></div>
            @empty <p class="text-muted mb-0">Belum ada log pada periode ini.</p> @endforelse
        </div></div>
    </div>
    <div class="col-lg-5 mb-4"><div class="card h-100"><div class="card-header"><h5 class="mb-0">Distribusi</h5></div><div class="card-body">
        <h6>HTTP Method</h6>@forelse($byMethod as $item)<span class="badge bg-label-primary me-1 mb-2">{{ $item->method }}: {{ number_format($item->hits) }}</span>@empty <span class="text-muted">-</span>@endforelse
        <h6 class="mt-3">Client</h6>@forelse($byClient as $item)<span class="badge bg-label-info me-1 mb-2">{{ $item->client_type }}: {{ number_format($item->hits) }}</span>@empty <span class="text-muted">-</span>@endforelse
        <h6 class="mt-3">Status Code</h6>@forelse($byStatus as $item)<span class="badge bg-label-{{ $item->status_code >= 500 ? 'danger' : ($item->status_code >= 400 ? 'warning' : 'success') }} me-1 mb-2">{{ $item->status_code }}: {{ number_format($item->hits) }}</span>@empty <span class="text-muted">-</span>@endforelse
    </div></div></div>
    <div class="col-lg-7 mb-4"><div class="card h-100"><div class="card-header"><h5 class="mb-0">Request per Hari</h5></div><div class="card-body d-flex align-items-end gap-1" style="height: 180px">
        @forelse($daily as $item)<div class="flex-fill text-center" title="{{ $item->day }}: {{ $item->hits }} hit"><div class="bg-primary rounded-top" style="height: {{ max(4, $item->hits / $maxDailyHits * 130) }}px"></div><small class="text-muted d-block text-truncate">{{ \Carbon\Carbon::parse($item->day)->format('d/m') }}</small></div>@empty <p class="text-muted">Belum ada data.</p> @endforelse
    </div></div></div>
    <div class="col-lg-5 mb-4"><div class="card h-100"><div class="card-header"><h5 class="mb-0">IP Paling Aktif</h5></div><div class="card-body p-0"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>IP</th><th class="text-end">Request</th></tr></thead><tbody>@forelse($topIps as $item)<tr><td><code>{{ $item->ip_address }}</code></td><td class="text-end">{{ number_format($item->hits) }}</td></tr>@empty<tr><td colspan="2" class="text-muted">Belum ada data.</td></tr>@endforelse</tbody></table></div></div></div></div>
</div>

<div class="card"><div class="card-header"><h5 class="mb-0">Log Request Terbaru</h5></div><div class="card-body table-responsive"><table class="table table-hover text-nowrap"><thead><tr><th>Waktu</th><th>Method</th><th>Endpoint</th><th>IP</th><th>Client</th><th>Status</th><th>User Agent</th></tr></thead><tbody>@forelse ($logs as $log)<tr><td>{{ $log->created_at?->format('d M Y H:i:s') }}</td><td><span class="badge bg-label-primary">{{ $log->method }}</span></td><td><code>{{ $log->endpoint }}</code></td><td>{{ $log->ip_address }}</td><td>{{ $log->client_type }}</td><td><span class="badge bg-label-{{ $log->status_code >= 500 ? 'danger' : ($log->status_code >= 400 ? 'warning' : 'success') }}">{{ $log->status_code }}</span></td><td class="text-truncate" style="max-width: 260px" title="{{ $log->user_agent }}">{{ $log->user_agent }}</td></tr>@empty<tr><td colspan="7" class="text-center text-muted">Belum ada log.</td></tr>@endforelse</tbody></table>{{ $logs->links('pagination::bootstrap-5') }}</div></div>
@endsection
