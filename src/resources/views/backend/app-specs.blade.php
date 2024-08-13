@extends('layouts/layoutMaster')

@section('title', 'Status List - Pages')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
@endsection

@section('page-script')
    @include('partials.success')
@endsection

@section('content')
    <div class="accordion" id="accordionPencarian">
        <div class="card mt-2">

            <div class="card-header border-bottom">
                <div class="row">
                    <div class="col-md">
                        <h5 class="card-title mb-3">Spesifikasi Aplikasi</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Item</th>
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
                            <td>Informasi Database</td>
                            <td>
                                Database: {{ $databaseInfo['database'] }}<br>
                                Host: {{ $databaseInfo['host'] }}
                            </td>
                        </tr>
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
                        <tr>
                            <td>Ekstensi PHP</td>
                            <td>{{ implode(', ', $phpExtensions) }}</td>
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
                            <td>Daftar Paket Composer</td>
                            <td>
                                <ul>
                                    @foreach ($composerPackages as $package)
                                        <li>{{ $package }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>Debug Mode</td>
                            <td>{{ $appDebug ? 'Aktif' : 'Nonaktif' }}</td>
                        </tr>
                        <tr>
                            <td>Lingkungan Aplikasi</td>
                            <td>{{ $appEnv }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
