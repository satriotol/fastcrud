@extends('layouts/layoutMaster')

@section('title', 'Atur Permission - ' . $role->name)

@section('content')
    @include('partials.success')

    <form action="{{ route('permission_manager.update', $role->id) }}" method="post">
        @csrf
        @method('put')

        <div class="card">
            <div class="card-header border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <h5 class="card-title mb-1">
                        Permission untuk role
                        <span class="badge bg-primary">{{ $role->name }}</span>
                    </h5>
                    <p class="text-muted mb-0">Centang permission yang boleh diakses role ini. Perubahan berlaku langsung setelah disimpan.</p>
                </div>
                <div class="d-flex gap-2">
                    @if (session('impersonator_id'))
                        <a href="{{ route('dashboard.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-eye me-1"></i> Lihat Hasil
                        </a>
                    @else
                        <a href="{{ route('permission_manager.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Simpan
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    @foreach ($permissions as $module => $items)
                        <div class="col-md-6 col-lg-4">
                            <div class="border rounded h-100">
                                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom bg-lighter">
                                    <strong class="text-uppercase">{{ str_replace('_', ' ', $module) }}</strong>
                                    <div class="form-check form-check-sm mb-0">
                                        <input class="form-check-input group-toggle" type="checkbox"
                                            id="toggle-{{ $loop->index }}" data-group="group-{{ $loop->index }}">
                                        <label class="form-check-label small" for="toggle-{{ $loop->index }}">Semua</label>
                                    </div>
                                </div>
                                <div class="p-3">
                                    @foreach ($items as $permission)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input group-group-{{ $loop->parent->index }}"
                                                type="checkbox" name="permissions[]"
                                                value="{{ $permission->name }}"
                                                id="perm-{{ $permission->id }}"
                                                data-group="group-{{ $loop->parent->index }}"
                                                @checked(in_array($permission->name, $assigned))>
                                            <label class="form-check-label" for="perm-{{ $permission->id }}">
                                                {{ \Illuminate\Support\Str::afterLast($permission->name, '-') }}
                                                <span class="text-muted small">({{ $permission->name }})</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
@endsection

@section('page-script')
    <script>
        document.querySelectorAll('.group-toggle').forEach(function(master) {
            var group = master.getAttribute('data-group');
            var members = document.querySelectorAll('input[data-group="' + group + '"]:not(.group-toggle)');

            // Set kondisi awal "Semua" berdasarkan anggota grup.
            master.checked = members.length > 0 && Array.from(members).every(function(cb) {
                return cb.checked;
            });

            master.addEventListener('change', function() {
                members.forEach(function(cb) {
                    cb.checked = master.checked;
                });
            });
        });
    </script>
@endsection
