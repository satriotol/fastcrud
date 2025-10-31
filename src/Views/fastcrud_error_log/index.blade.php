@extends('layouts/layoutMaster')

@section('title', 'Fastcrud Beta Mode List - Pages')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    @include('partials.success')
@endsection

@section('content')
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold py-3 mb-2">
                        <i class="ti ti-file-text me-2"></i>Fastcrud Beta Mode
                    </h4>
                    <p class="text-muted mb-0">Beta Mode Buatan Satrio Tamvan</p>
                </div>
                <div class="d-flex gap-2">
                </div>
            </div>
        </div>
    </div>

    <!-- Search Filter -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <i class="ti ti-search me-2"></i>
                <h5 class="card-title mb-0">Filter Pencarian</h5>
            </div>
        </div>
        <div class="card-body">
            <form action="" method="GET">
                <div class="row g-3">
                    <div class="col-md-4 mb-3">
                        {{ html()->label('Error Code')->class('form-label') }}
                        {{ html()->text('error_code', old('error_code'))->class('form-control')->placeholder('Cari Error Code') }}
                    </div>
                    <div class="col-md-4 mb-3">
                        {{ html()->label('Url')->class('form-label') }}
                        {{ html()->text('url', old('url'))->class('form-control')->placeholder('Cari Url') }}
                    </div>
                    <div class="col-md-4 mb-3">
                        {{ html()->label('Method')->class('form-label') }}
                        {{ html()->text('method', old('method'))->class('form-control')->placeholder('Cari Method') }}
                    </div>
                    <div class="col-md-4 mb-3">
                        {{ html()->label('User Id')->class('form-label') }}
                        {{ html()->text('user_id', old('user_id'))->class('form-control')->placeholder('Cari User Id') }}
                    </div>
                    <div class="col-md-4 mb-3">
                        {{ html()->label('IP Address')->class('form-label') }}
                        {{ html()->text('ip_address', old('ip_address'))->class('form-control')->placeholder('Cari IP Address') }}
                    </div>
                    <div class="col-md-4 mb-3">
                        {{ html()->label('Environment')->class('form-label') }}
                        {{ html()->text('environment', old('environment'))->class('form-control')->placeholder('Cari Environment') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit">
                            <i class="ti ti-search me-1"></i>Cari Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @include('partials.errors')

    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">
                        <i class="ti ti-list me-2"></i>Daftar Fastcrud Beta Mode
                    </h5>
                    <p class="text-muted small mb-0 mt-1">
                        Total {{ $fastcrud_error_logs->total() }} data ditemukan
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-label-primary">
                        Halaman {{ $fastcrud_error_logs->currentPage() }} dari {{ $fastcrud_error_logs->lastPage() }}
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if ($fastcrud_error_logs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th>Tanggal</th>
                                <th>Message</th>
                                <th>Error</th>
                                <th>URL</th>
                                <th>Method</th>
                                <th>User</th>
                                <th>IP</th>
                                <th>Env</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = ($fastcrud_error_logs->currentPage() - 1) * $fastcrud_error_logs->perPage() + 1;
                            @endphp
                            @foreach ($fastcrud_error_logs as $row)
                                <tr>
                                    <td class="text-center fw-semibold">{{ $no++ }}</td>
                                    <td>{{ $row->created_at }}</td>

                                    {{-- Message --}}
                                    <td>
                                        <span title="{{ $row->message }}">
                                            {{ \Illuminate\Support\Str::limit($row->message, 60) }}
                                        </span>
                                        <br>
                                        <a class="text-primary small" data-bs-toggle="collapse"
                                            href="#logTrace{{ $row->id }}" role="button">
                                            detail &rsaquo;
                                        </a>
                                    </td>

                                    {{-- Error code --}}
                                    <td class="text-nowrap">{{ $row->error_code ?? '-' }}</td>

                                    {{-- URL --}}
                                    <td>
                                        <span title="{{ $row->url }}">
                                            {{ $row->url }}
                                        </span>
                                    </td>

                                    {{-- Method --}}
                                    <td>
                                        <span class="badge bg-dark">{{ $row->method }}</span>
                                    </td>

                                    {{-- User --}}
                                    <td>{{ $row->user_id ?? '-' }}</td>

                                    {{-- IP --}}
                                    <td>{{ $row->ip_address ?? '-' }}</td>

                                    {{-- Env --}}
                                    <td>
                                        <span class="badge bg-secondary">{{ $row->environment }}</span>
                                    </td>
                                </tr>

                                {{-- Collapse Detail --}}
                                <tr class="p-0 border-0">
                                    <td colspan="14" class="p-0 border-0">
                                        <div id="logTrace{{ $row->id }}" class="collapse bg-light p-3 small">
                                            <strong>Trace:</strong>
                                            <pre class="text-muted small" style="white-space: pre-wrap;">{{ $row->trace }}</pre>

                                            <strong>Input:</strong>
                                            <pre class="text-muted small" style="white-space: pre-wrap;">{{ $row->input }}</pre>

                                            <strong>User Agent:</strong>
                                            <pre class="text-muted small" style="white-space: pre-wrap;">{{ $row->user_agent }}</pre>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="ti ti-file-x" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="mb-2">Tidak Ada Data</h5>
                    <p class="text-muted mb-3">Belum ada Fastcrud Beta Mode yang tersedia atau sesuai dengan filter
                        pencarian.</p>
                </div>
            @endif
        </div>

        @if ($fastcrud_error_logs->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $fastcrud_error_logs->firstItem() }} - {{ $fastcrud_error_logs->lastItem() }}
                        dari {{ $fastcrud_error_logs->total() }} data
                    </div>
                    <div>
                        {{ $fastcrud_error_logs->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
