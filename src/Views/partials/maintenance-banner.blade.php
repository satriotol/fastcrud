@if (\Satriotol\Fastcrud\Middleware\MaintenanceMode::active())
    <div class="bg-danger text-white border-bottom shadow-sm d-print-none" style="position: sticky; top: 0; z-index: 1081;">
        <div class="container-fluid py-2 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="ti ti-tool ti-md"></i>
                <span>
                    <strong>Mode Maintenance aktif</strong> &mdash;
                    hanya SUPERADMIN yang bisa mengakses aplikasi.
                </span>
            </div>
            @role('SUPERADMIN')
                <a href="{{ route('maintenance.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="ti ti-settings me-1"></i> Atur Maintenance
                </a>
            @endrole
        </div>
    </div>
@endif
