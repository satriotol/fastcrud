@extends('layouts/layoutMaster')

@section('title', 'Mode Maintenance')

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Superadmin/</span> Mode Maintenance</h4>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Mode Maintenance</h5>
                    <span class="badge {{ $active ? 'bg-danger' : 'bg-success' }}">
                        {{ $active ? 'AKTIF' : 'NONAKTIF' }}
                    </span>
                </div>
                <div class="card-body">
                    @if ($protected === 0)
                        <div class="alert alert-warning d-flex align-items-center gap-2" role="alert">
                            <i class="ti ti-alert-triangle"></i>
                            <div>
                                <strong>Belum ada route yang ditandai.</strong>
                                Menyalakan mode maintenance tidak akan memblokir apa pun.
                                Tandai dulu route group-nya seperti contoh di bawah.
                            </div>
                        </div>
                    @endif

                    @if ($active)
                        <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                            <i class="ti ti-alert-triangle"></i>
                            <div>
                                Aplikasi sedang terkunci. Hanya SUPERADMIN &mdash; dan sesi
                                <strong>Lihat Sebagai</strong> yang dimulai SUPERADMIN &mdash; yang bisa mengakses.
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('maintenance.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="active" value="1"
                                id="active" {{ old('active', $active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">Aktifkan mode maintenance</label>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="message">Pesan untuk pengguna</label>
                            <textarea class="form-control" name="message" id="message" rows="3"
                                placeholder="Sistem sedang dalam perbaikan. Silakan coba beberapa saat lagi.">{{ old('message', $message) }}</textarea>
                            @error('message')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <small class="text-muted">Dikosongkan berarti memakai pesan bawaan.</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
                <div class="card-footer">
                    <p class="mb-2">
                        <i class="ti ti-shield-check me-1"></i>
                        @if ($protected > 0)
                            <strong>{{ $protected }}</strong> route terlindungi saat maintenance aktif.
                        @else
                            Cara menandai route group yang ikut terkunci:
                        @endif
                    </p>
                    <pre class="mb-2"><code>Route::middleware(['auth', 'maintenance'])->group(function () {
    // route yang ikut terkunci saat maintenance
});</code></pre>
                    <small class="text-muted">
                        Dua kandidat yang sudah ada di instalasi ini:
                        <code>routes/web.php</code> (dashboard, <code>/user</code>, dan seluruh CRUD hasil
                        generator) dan group <code>auth</code> di route paket (panel admin).
                        Jalankan <code>php artisan route:clear</code> setelah mengubah.
                        Jangan tandai route login &mdash; itu satu-satunya jalan masuk superadmin.
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection
