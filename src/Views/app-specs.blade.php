@extends('layouts/layoutMaster')

@section('title', 'Spesifikasi Aplikasi')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
@endsection

@section('page-style')
    <style>
        @media print {

            #layout-menu,
            .layout-navbar,
            .content-footer,
            .layout-overlay,
            .drag-target,
            .content-backdrop,
            .buy-now,
            .template-customizer,
            .d-print-none {
                display: none !important;
            }

            .layout-page,
            .content-wrapper,
            .container-p-y,
            .container-fluid,
            .container-xxl {
                padding: 0 !important;
                margin: 0 !important;
            }

            body {
                background: #fff !important;
            }

            .card {
                box-shadow: none !important;
                border: 0 !important;
                margin-bottom: .75rem !important;
            }

            .card-header,
            .card-body {
                padding: .25rem 0 !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            table {
                font-size: 9px !important;
                width: 100% !important;
            }

            /* repeat table headers on every printed page */
            thead {
                display: table-header-group;
            }

            tr,
            h5,
            h6 {
                break-inside: avoid;
            }

            a {
                text-decoration: none !important;
                color: #000 !important;
            }

            @page {
                margin: 12mm;
            }
        }
    </style>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
@endsection

@section('page-script')
    @include('partials.success')
@endsection

@section('content')
    {{-- Kop dokumen, hanya muncul saat dicetak --}}
    <div class="d-none d-print-block mb-3 text-center border-bottom pb-2">
        <h4 class="mb-0">{{ $appName }}</h4>
        <h5 class="mb-1">SPESIFIKASI APLIKASI</h5>
        <small>Dicetak oleh {{ $generatedBy }} pada {{ $generatedAt->format('d/m/Y H:i:s') }}</small>
    </div>

    <div class="card mt-2">
        <div class="card-header border-bottom">
            <div class="row">
                <div class="col-md">
                    <h5 class="card-title mb-3">Spesifikasi Aplikasi</h5>
                </div>
                <div class="col-md">
                    <div
                        class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                        <div class="dt-buttons btn-group flex-wrap">
                            <button type="button" onclick="window.print()"
                                class="btn btn-secondary btn-primary d-print-none">
                                <span><i class="ti ti-printer me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Cetak / PDF</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">

            {{-- 1. Identitas Aplikasi --}}
            <h6 class="fw-bold text-uppercase mt-2">1. Identitas Aplikasi</h6>
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th style="width:30%">Item</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Nama Aplikasi</td>
                        <td>{{ $appName }}</td>
                    </tr>
                    <tr>
                        <td>Versi FastCRUD</td>
                        <td>{{ $fastcrudVersion }}</td>
                    </tr>
                    <tr>
                        <td>Lingkungan Aplikasi</td>
                        <td>{{ $appEnv }}</td>
                    </tr>
                    <tr>
                        <td>Debug Mode</td>
                        <td>{{ $appDebug ? 'Aktif' : 'Nonaktif' }}</td>
                    </tr>
                    <tr>
                        <td>URL Aplikasi</td>
                        <td>{{ $appUrl }}</td>
                    </tr>
                    <tr>
                        <td>Zona Waktu</td>
                        <td>{{ $appTimezone }}</td>
                    </tr>
                    <tr>
                        <td>Bahasa (Locale)</td>
                        <td>{{ $appLocale }}</td>
                    </tr>
                    <tr>
                        <td>Dicetak Oleh</td>
                        <td>{{ $generatedBy }} &mdash; {{ $generatedAt->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- 2. Lingkungan Server --}}
            <h6 class="fw-bold text-uppercase mt-4">2. Lingkungan Server</h6>
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th style="width:30%">Item</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Server Software</td>
                        <td>{{ $serverSoftware }}</td>
                    </tr>
                    <tr>
                        <td>Informasi Sistem Operasi</td>
                        <td>{{ $osInfo }}</td>
                    </tr>
                    <tr>
                        <td>Versi PHP</td>
                        <td>{{ $phpVersion }}</td>
                    </tr>
                    <tr>
                        <td>Pengaturan PHP</td>
                        <td>
                            Memory Limit: {{ $phpIniSettings['memory_limit'] }}<br>
                            Max Execution Time: {{ $phpIniSettings['max_execution_time'] }}<br>
                            Upload Max Filesize: {{ $phpIniSettings['upload_max_filesize'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>Ekstensi PHP ({{ count($phpExtensions) }})</td>
                        <td>{{ implode(', ', $phpExtensions) }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- 3. Framework & Runtime --}}
            <h6 class="fw-bold text-uppercase mt-4">3. Framework &amp; Runtime</h6>
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th style="width:30%">Item</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Versi Laravel</td>
                        <td>{{ $laravelVersion }}</td>
                    </tr>
                    <tr>
                        <td>Driver Database</td>
                        <td>{{ $databaseDriver }}</td>
                    </tr>
                    <tr>
                        <td>Driver Cache</td>
                        <td>{{ $cacheDriver }}</td>
                    </tr>
                    <tr>
                        <td>Driver Queue</td>
                        <td>{{ $queueDriver }}</td>
                    </tr>
                    <tr>
                        <td>Driver Session</td>
                        <td>{{ $sessionDriver }}</td>
                    </tr>
                    <tr>
                        <td>Durasi Sesi Login (Menit)</td>
                        <td>{{ $sessionLifetime }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- 4. Struktur Database --}}
            <h6 class="fw-bold text-uppercase mt-4">4. Struktur Database</h6>
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th style="width:30%">Item</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Nama Koneksi</td>
                        <td>{{ $databaseInfo['connection'] }}</td>
                    </tr>
                    <tr>
                        <td>Nama Database</td>
                        <td>{{ $databaseInfo['database'] }}</td>
                    </tr>
                    <tr>
                        <td>Host</td>
                        <td>{{ $databaseInfo['host'] }}{{ $databaseInfo['port'] ? ':' . $databaseInfo['port'] : '' }}</td>
                    </tr>
                    <tr>
                        <td>Versi Server Database</td>
                        <td>{{ $databaseVersion }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Tabel</td>
                        <td>{{ count($databaseTables) }}</td>
                    </tr>
                </tbody>
            </table>

            @if (count($databaseTables))
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Tabel</th>
                                <th class="text-end">Jumlah Baris</th>
                                <th class="text-end">Ukuran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($databaseTables as $index => $table)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $table['name'] }}</td>
                                    <td class="text-end">
                                        {{ is_null($table['rows']) ? '-' : number_format($table['rows'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-end">
                                        {{ !empty($table['size']) ? number_format($table['size'] / 1024, 1, ',', '.') . ' KB' : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning">Struktur database tidak dapat dibaca pada koneksi ini.</div>
            @endif

            {{-- 5. Modul & Fitur --}}
            <h6 class="fw-bold text-uppercase mt-4">5. Modul &amp; Fitur ({{ count($modules) }} modul)</h6>
            @foreach ($modules as $moduleName => $routes)
                <div class="mb-3">
                    <strong>{{ $moduleName }}</strong> <span class="text-muted">({{ count($routes) }} route)</span>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="width:10%">Method</th>
                                    <th style="width:30%">URI</th>
                                    <th style="width:25%">Nama Route</th>
                                    <th>Middleware</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($routes as $route)
                                    <tr>
                                        <td>{{ $route['methods'] }}</td>
                                        <td>{{ $route['uri'] }}</td>
                                        <td>{{ $route['name'] }}</td>
                                        <td>{{ $route['middleware'] ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            {{-- 6. Hak Akses --}}
            <h6 class="fw-bold text-uppercase mt-4">6. Hak Akses</h6>
            @if ($roles->count())
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th class="text-end">Jumlah Permission</th>
                            <th class="text-end">Jumlah User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td class="text-end">{{ $role->permissions_count }}</td>
                                <td class="text-end">{{ $role->users_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Permission</th>
                                @foreach ($roles as $role)
                                    <th class="text-center">{{ $role->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissionGroups as $groupName => $permissions)
                                <tr>
                                    <td colspan="{{ $roles->count() + 1 }}" class="fw-bold">{{ $groupName }}</td>
                                </tr>
                                @foreach ($permissions as $permission)
                                    @php $owners = $permission->roles->pluck('name'); @endphp
                                    <tr>
                                        <td class="ps-4">{{ $permission->name }}</td>
                                        @foreach ($roles as $role)
                                            <td class="text-center">{{ $owners->contains($role->name) ? '✓' : '' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning">Data role dan permission tidak dapat dibaca.</div>
            @endif

            {{-- 7. Dependensi --}}
            <h6 class="fw-bold text-uppercase mt-4">7. Dependensi ({{ count($composerPackages) }} paket)</h6>
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Paket</th>
                        <th>Versi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($composerPackages as $name => $version)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $name }}</td>
                            <td>{{ $version }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">composer.lock tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
@endsection
