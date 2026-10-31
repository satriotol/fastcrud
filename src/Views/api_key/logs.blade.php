@extends('layouts/layoutMaster')
@section('title', 'API Key Logs')
@section('content')
<div class="card mt-2">
    <div class="card-header d-flex justify-content-between"><h5>Log: {{ $api_key->note ?: $api_key->uuid }}</h5><a href="{{ route('api_key.index') }}" class="btn btn-warning">Kembali</a></div>
    <div class="card-body table-responsive">
        <table class="table text-nowrap"><thead><tr><th>Waktu</th><th>Method</th><th>Endpoint</th><th>IP</th><th>Client</th><th>Status</th><th>User Agent</th></tr></thead>
        <tbody>@foreach ($logs as $log)<tr><td>{{ $log->created_at }}</td><td>{{ $log->method }}</td><td>{{ $log->endpoint }}</td><td>{{ $log->ip_address }}</td><td>{{ $log->client_type }}</td><td>{{ $log->status_code }}</td><td>{{ $log->user_agent }}</td></tr>@endforeach</tbody></table>
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
