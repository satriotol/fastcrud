@extends('layouts/layoutMaster')

@section('title', 'Audit - Pages')

@section('vendor-style')
    <!-- Menggunakan Bootstrap bawaan, tanpa custom CSS berat -->
@endsection

@section('vendor-script')
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show loading state on form submit
            document.querySelector('form').addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-1"></span>Mencari...';
                }
            });

            // Tooltip initialization
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Expandable audit values
            document.querySelectorAll('.audit-values').forEach(function(element) {
                if (element.scrollHeight > element.clientHeight) {
                    element.style.cursor = 'pointer';
                    element.title = 'Klik untuk expand/collapse';
                    element.addEventListener('click', function() {
                        if (this.style.maxHeight === 'none') {
                            this.style.maxHeight = '200px';
                        } else {
                            this.style.maxHeight = 'none';
                        }
                    });
                }
            });
        });
    </script>
@endsection

@section('content')
    <!-- Header dengan Informasi -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 bg-primary text-white">
                <div class="card-body py-3 px-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1"><i class="ti ti-history me-2"></i> Audit Log Sistem</h4>
                            <small class="text-white-50">Pantau dan lacak semua aktivitas dan perubahan data dalam sistem secara real-time</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-light text-dark">Total Log: {{ $audits->total() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0">
        <!-- Filter Section -->
        <div class="card-header bg-light border-0 py-2">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-filter me-2 text-primary"></i>
                        Filter Audit Log
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body pb-0 pt-2">
        <form action="" class="mb-3">
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        <i class="ti ti-activity me-1"></i>Event/Action
                    </label>
                    {{ html()->text('event')->class('form-control')->placeholder('Contoh: created, updated, deleted')->value(@old('event')) }}
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        <i class="ti ti-calendar me-1"></i>Tanggal
                    </label>
                    {{ html()->date('created_at')->class('form-control')->placeholder('Pilih tanggal')->value(@old('created_at')) }}
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        <i class="ti ti-user me-1"></i>User ID
                    </label>
                    {{ html()->text('user_id')->class('form-control')->placeholder('Masukkan ID atau nama user')->value(@old('user_id')) }}
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        <i class="ti ti-id me-1"></i>ID Model
                    </label>
                    {{ html()->text('auditable_id')->class('form-control')->placeholder('Masukkan ID Model')->value(@old('auditable_id')) }}
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        <i class="ti ti-database me-1"></i>Tipe Model
                    </label>
                    {{ html()->text('auditable_type')->class('form-control')->placeholder('Contoh: User, Operational')->value(@old('auditable_type')) }}
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        <i class="ti ti-world me-1"></i>IP Address
                    </label>
                    {{ html()->text('ip_address')->class('form-control')->placeholder('Masukkan IP Address')->value(@old('ip_address')) }}
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 text-end">
                    <button class="btn btn-primary" type="submit">
                        <i class="ti ti-search me-1"></i>
                        Cari Data
                    </button>
                </div>
            </div>
        </form>
        <div class="alert alert-info mt-2">
            <i class="ti ti-info-circle me-2"></i>
            <b>Tips:</b> Gunakan filter di atas untuk mencari log tertentu. Kolom <b>Nilai Lama</b> dan <b>Nilai Baru</b>
            dapat di-klik untuk melihat detail lebih banyak jika datanya panjang.
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-body pt-0">
        @if ($audits->count() > 0)
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                                <th>No</th>
                                <th>IP Address<br><span class="text-muted small">IP pengguna</span></th>
                                <th>Action<br><span class="text-muted small">Jenis aksi</span></th>
                                <th>Model Info<br><span class="text-muted small">Tipe & ID</span></th>
                                <th>User<br><span class="text-muted small">Nama & ID</span></th>
                                <th>Nilai Lama<br><span class="text-muted small">Sebelum</span></th>
                                <th>Nilai Baru<br><span class="text-muted small">Sesudah</span></th>
                                <th>Waktu<br><span class="text-muted small">Tanggal & Jam</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = ($audits->currentPage() - 1) * $audits->perPage() + 1;
                        @endphp
                        @foreach ($audits as $audit)
                            <tr>
                                <td>{{ $no++ }}</td>

                                <!-- IP Address -->
                                <td>
                                    <span class="badge bg-light text-dark" data-bs-toggle="tooltip" title="IP Address pengguna">
                                        {{ $audit->ip_address }}
                                    </span>
                                </td>

                                <!-- Action/Event -->
                                <td>
                                    <span class="badge bg-secondary text-uppercase">
                                        {{ $audit->event }}
                                    </span>
                                </td>

                                <!-- Model Info -->
                                <td>
                                    <div>
                                        <span class="badge bg-info text-dark mb-1">{{ $audit->auditable_type }}</span><br>
                                        <span class="badge bg-light text-dark">ID: {{ $audit->auditable_id }}</span>
                                    </div>
                                </td>

                                <!-- User Info -->
                                <td>
                                    <div>
                                        @if ($audit->user)
                                            <span class="badge bg-primary">{{ $audit->user->name }}</span><br>
                                            <small class="text-muted">ID: {{ $audit->user_id }}</small>
                                        @else
                                            <span class="badge bg-secondary"><i class="ti ti-robot"></i> System</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Old Values -->
                                <td>
                                    @if (!empty($audit->old_values))
                                        <div style="max-height:120px;overflow-y:auto;font-size:0.9em;" data-bs-toggle="tooltip" title="Klik untuk expand">
                                            <ul class="mb-0 ps-2">
                                                @foreach ($audit->old_values as $key => $old_value)
                                                    <li>
                                                        <strong>{{ $key }}:</strong>
                                                        <span class="text-muted">
                                                            {{ is_string($old_value) ? Str::limit($old_value, 50) : $old_value }}
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">
                                            <i class="ti ti-minus"></i> Tidak ada
                                        </span>
                                    @endif
                                </td>

                                <!-- New Values -->
                                <td>
                                    @if (!empty($audit->new_values))
                                        <div style="max-height:120px;overflow-y:auto;font-size:0.9em;" data-bs-toggle="tooltip" title="Klik untuk expand">
                                            <ul class="mb-0 ps-2">
                                                @foreach ($audit->new_values as $key => $new_value)
                                                    <li>
                                                        <strong>{{ $key }}:</strong>
                                                        <span class="text-success">
                                                            {{ is_string($new_value) ? Str::limit($new_value, 50) : $new_value }}
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">
                                            <i class="ti ti-minus"></i> Tidak ada
                                        </span>
                                    @endif
                                </td>

                                <!-- Date Time -->
                                <td>
                                    <div>
                                        <span class="badge bg-light text-dark">{{ $audit->created_at->format('d M Y') }}</span><br>
                                        <small class="text-muted">{{ $audit->created_at->format('H:i:s') }}</small><br>
                                        <small class="text-muted">{{ $audit->created_at->diffForHumans() }}</small>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $audits->appends($_GET)->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="ti ti-search-off" style="font-size:2.5rem;opacity:0.5;"></i>
                <h6 class="mb-2">Tidak Ada Data Audit</h6>
                <p class="mb-0">Belum ada log audit yang ditemukan atau sesuai dengan filter yang diterapkan.</p>
            </div>
        @endif
    </div>
    </div>
@endsection
