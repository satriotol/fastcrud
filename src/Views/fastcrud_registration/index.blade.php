@extends('layouts/layoutMaster')

@section('title', 'Antrian Pendaftaran')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
    @include('partials.success')
    <script>
        // select2 di dalam modal harus di-init saat modal tampil, dengan dropdownParent modal itu sendiri
        $(document).on('shown.bs.modal', '.modal', function() {
            $(this).find('.select2').select2({
                dropdownParent: $(this),
                width: '100%'
            });
        });
    </script>
@endsection

@section('content')
    <div class="row g-3 align-items-stretch mb-3">
        <div class="col-lg-8 col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white rounded-top">
                    <i class="ti ti-search me-2"></i> Pencarian Pendaftar
                </div>
                <div class="card-body">
                    <form action="">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold"><i class="ti ti-user me-1"></i> Nama</label>
                                {{ html()->text('name')->class('form-control')->placeholder('Cari Nama')->value(@old('name')) }}
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold"><i class="ti ti-mail me-1"></i> E-mail</label>
                                {{ html()->text('email')->class('form-control')->placeholder('Cari E-mail')->value(@old('email')) }}
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold"><i class="ti ti-flag me-1"></i> Status</label>
                                {{ html()->select('status', ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'])->class('form-select')->placeholder('Semua Status')->value(@old('status')) }}
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button class="btn btn-primary btn-sm px-4" type="submit">
                                <i class="ti ti-search me-1"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="ti ti-clock text-warning me-1"></i> Menunggu</span>
                        <span class="badge bg-warning">{{ $counts['pending'] ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="ti ti-check text-success me-1"></i> Disetujui</span>
                        <span class="badge bg-success">{{ $counts['approved'] ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span><i class="ti ti-x text-danger me-1"></i> Ditolak</span>
                        <span class="badge bg-danger">{{ $counts['rejected'] ?? 0 }}</span>
                    </div>
                    <label class="form-label fw-semibold small">Link Pendaftaran</label>
                    <div class="input-group input-group-sm">
                        <input type="text" id="registerLink" class="form-control" readonly
                            value="{{ \Satriotol\Fastcrud\Models\FastcrudRegistration::linkUrl() }}">
                        <button class="btn btn-outline-primary" type="button" onclick="copyRegisterLink(this)">
                            <i class="ti ti-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white rounded-top d-flex justify-content-between align-items-center">
            <div>
                <i class="ti ti-user-plus me-2"></i> Antrian Pendaftaran
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th><i class="ti ti-hash"></i> No</th>
                            <th><i class="ti ti-user"></i> Nama</th>
                            <th><i class="ti ti-mail"></i> E-mail</th>
                            <th><i class="ti ti-note"></i> Keperluan</th>
                            <th><i class="ti ti-flag"></i> Status</th>
                            <th><i class="ti ti-calendar"></i> Tanggal Daftar</th>
                            <th><i class="ti ti-user-check"></i> Diproses Oleh</th>
                            <th><i class="ti ti-settings"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = ($registrations->currentPage() - 1) * $registrations->perPage() + 1;
                        @endphp
                        @forelse ($registrations as $registration)
                            <tr>
                                <td class="text-center fw-bold">{{ $no++ }}</td>
                                <td class="fw-semibold">{{ $registration->name }}</td>
                                <td>{{ $registration->email }}</td>
                                <td class="text-wrap" style="max-width: 260px;">
                                    {{ $registration->note ?: '-' }}
                                    @if ($registration->reject_reason)
                                        <br><small class="text-danger"><i class="ti ti-x me-1"></i>
                                            {{ $registration->reject_reason }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($registration->status == 'pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @elseif ($registration->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ $registration->created_at }}</td>
                                <td>
                                    {{ $registration->processor->name ?? '-' }}
                                    @if ($registration->processed_at)
                                        <br><small class="text-muted">{{ $registration->processed_at }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-light btn-sm px-2 py-1 dropdown-toggle"
                                            data-bs-toggle="dropdown" title="Aksi"><i class="ti ti-settings"></i></button>
                                        <div class="dropdown-menu">
                                            @if ($registration->status == 'pending')
                                                @can('fastcrud_registration-approve')
                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#approve-{{ $registration->id }}">
                                                        <i class="ti ti-check me-1"></i> Setujui
                                                    </button>
                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#reject-{{ $registration->id }}">
                                                        <i class="ti ti-x me-1"></i> Tolak
                                                    </button>
                                                @endcan
                                            @endif
                                            @can('fastcrud_registration-delete')
                                                <form action="{{ route('fastcrud_registration.destroy', $registration->uuid) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pendaftaran ini?')">
                                                        <i class="ti ti-trash me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="ti ti-inbox fs-3 d-block mb-2"></i>
                                    Belum ada pengajuan pendaftaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $registrations->appends($_GET)->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- Modal ditaruh di luar <table> — di dalam tbody HTML-nya invalid dan browser akan memindahkannya --}}
    @can('fastcrud_registration-approve')
        @foreach ($registrations->where('status', 'pending') as $registration)
            <div class="modal fade" id="approve-{{ $registration->id }}" tabindex="-1" aria-hidden="true">
                <form action="{{ route('fastcrud_registration.approve', $registration->uuid) }}" method="post">
                    @csrf
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5"><i class="ti ti-check me-1"></i> Setujui
                                    {{ $registration->name }}</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-start">
                                <p class="text-muted small mb-3">
                                    Akun dibuat dengan e-mail <strong>{{ $registration->email }}</strong> dan password
                                    yang diisi sendiri oleh pendaftar.
                                </p>
                                <div class="form-group">
                                    {{ html()->label('Role')->for('role-' . $registration->id) }}
                                    {{ html()->select('role[]', $roles->pluck('name', 'name'))->id('role-' . $registration->id)->class('form-control select2')->placeholder('Pilih Role')->required(true)->multiple() }}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-success">Setujui</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal fade" id="reject-{{ $registration->id }}" tabindex="-1" aria-hidden="true">
                <form action="{{ route('fastcrud_registration.reject', $registration->uuid) }}" method="post">
                    @csrf
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5"><i class="ti ti-x me-1"></i> Tolak
                                    {{ $registration->name }}</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-start">
                                <div class="form-group">
                                    {{ html()->label('Alasan (opsional)')->for('reject_reason-' . $registration->id) }}
                                    {{ html()->textarea('reject_reason')->id('reject_reason-' . $registration->id)->class('form-control')->rows(3)->placeholder('Alasan penolakan') }}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-danger">Tolak</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach
    @endcan

    <script>
        function copyRegisterLink(btn) {
            const input = document.getElementById('registerLink');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value).then(() => {
                btn.innerHTML = '<i class="ti ti-check"></i>';
                setTimeout(() => btn.innerHTML = '<i class="ti ti-copy"></i>', 1500);
            });
        }
    </script>
@endsection
