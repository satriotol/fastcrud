@if (session('impersonator_id'))
    @php
        $impersonatedUser = auth()->user();
        $impersonatedRole = $impersonatedUser?->roles->first();
    @endphp
    <div class="bg-warning text-dark border-bottom shadow-sm d-print-none" style="position: sticky; top: 0; z-index: 1080;">
        <div class="container-fluid py-2 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="ti ti-eye ti-md"></i>
                <span>
                    Mode <strong>Lihat Sebagai</strong>:
                    <strong>{{ $impersonatedUser->name }}</strong>
                    @if ($impersonatedRole)
                        <span class="badge bg-dark">{{ $impersonatedRole->name }}</span>
                    @endif
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                @if ($impersonatedRole && $impersonatedRole->name !== 'SUPERADMIN')
                    <a href="{{ route('permission_manager.edit', $impersonatedRole->id) }}"
                        class="btn btn-sm btn-dark">
                        <i class="ti ti-adjustments me-1"></i> Atur Permission Role Ini
                    </a>
                @endif
                <a href="{{ route('impersonate.stop') }}" class="btn btn-sm btn-outline-dark">
                    <i class="ti ti-arrow-back-up me-1"></i> Kembali ke Superadmin
                </a>
            </div>
        </div>
    </div>
@endif
