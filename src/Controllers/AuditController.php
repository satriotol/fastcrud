<?php

namespace Satriotol\Fastcrud\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Satriotol\Fastcrud\Exports\AuditsExport;
use Satriotol\Fastcrud\Repositories\FastcrudAuditRepository;

class AuditController extends Controller
{
    /**
     * Label filter yang ditampilkan sebagai chip pada halaman index.
     */
    private const FILTER_LABELS = [
        'event'          => 'Event',
        'user_id'        => 'User ID',
        'auditable_type' => 'Tipe Model',
        'auditable_id'   => 'ID Model',
        'ip_address'     => 'Alamat IP',
        'created_at'     => 'Tanggal',
        'created_from'   => 'Dari Tanggal',
        'created_to'     => 'Sampai Tanggal',
    ];

    protected $fastcrudAuditRepository;
    public function __construct()
    {
        $this->fastcrudAuditRepository = new FastcrudAuditRepository();
        $this->middleware('role:SUPERADMIN|IMPERSONATE');
    }

    public function index(Request $request)
    {
        // simplePaginate dipakai agar tidak ada query COUNT(*) ke tabel audits.
        // Pada tabel berukuran jutaan baris, COUNT(*) InnoDB harus memindai
        // index sehingga menjadi penyebab utama slow query di halaman ini.
        $audits = $this->fastcrudAuditRepository->getAll([], $request)
            ->with('user')
            ->orderByDesc('id')
            ->simplePaginate(15)
            ->appends($request->query());

        $this->preloadUserRoles($audits->getCollection());

        // Jumlah aktivitas per tanggal dihitung dari record halaman ini saja,
        // sehingga tidak ada query agregasi tambahan ke tabel audits.
        $dayCounts = $audits->getCollection()
            ->groupBy(fn($audit) => $audit->created_at->toDateString())
            ->map->count();

        $activeFilters = $this->activeFilters($request);

        $request->flash();
        return view('fastcrud::fastcrud_audit.index', compact('audits', 'dayCounts', 'activeFilters'));
    }

    /**
     * Daftar filter yang sedang aktif untuk ditampilkan sebagai chip,
     * sehingga user tahu kenapa hasilnya menyempit dan bisa menghapusnya satu per satu.
     */
    private function activeFilters(Request $request): array
    {
        $filters = [];

        foreach (self::FILTER_LABELS as $key => $label) {
            $value = $request->query($key);

            if (is_array($value) || $value === null || trim((string) $value) === '') {
                continue;
            }

            $filters[] = [
                'key'   => $key,
                'label' => $label,
                'value' => trim((string) $value),
            ];
        }

        return $filters;
    }

    /**
     * Eager load relasi role milik user audit (bila paket role tersedia) agar
     * pemanggilan getRoleNames() pada view tidak menimbulkan query N+1.
     */
    private function preloadUserRoles(Collection $audits): void
    {
        $users = $audits->pluck('user')->filter();

        if ($users->isEmpty()) {
            return;
        }

        $users->groupBy(fn($user) => get_class($user))->each(function (Collection $group) {
            $sample = $group->first();

            if (!method_exists($sample, 'getRoleNames') || $sample->relationLoaded('roles')) {
                return;
            }

            $unique = $group->unique(fn($user) => $user->getKey())->values()->all();

            EloquentCollection::make($unique)->load('roles');
        });
    }

    /**
     * Ambil record audit terbaru sesuai filter aktif, dibatasi
     * config fastcrud.audit_export_limit agar export tetap ringan.
     */
    private function getExportRecords(Request $request)
    {
        $limit = config('fastcrud.audit_export_limit', 500);

        return $this->fastcrudAuditRepository->getAll([], $request)
            ->with('user')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function exportExcel(Request $request)
    {
        $audits = $this->getExportRecords($request);

        return Excel::download(new AuditsExport($audits), 'audit-log-' . now()->format('Y-m-d-Hi') . '.xlsx');
    }
}
