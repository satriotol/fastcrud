<?php

namespace Satriotol\Fastcrud\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Satriotol\Fastcrud\Exports\AuditsExport;
use Satriotol\Fastcrud\Repositories\FastcrudAuditRepository;

class AuditController extends Controller
{
    protected $fastcrudAuditRepository;
    public function __construct()
    {
        $this->fastcrudAuditRepository = new FastcrudAuditRepository();
        $this->middleware('role:SUPERADMIN|IMPERSONATE');
    }

    public function index(Request $request)
    {
        $audits = $this->fastcrudAuditRepository->getAll([], $request)->with('user')->orderByDesc('id')->paginate(10);

        // Total aktivitas per hari (bukan hanya per halaman) untuk header grup tanggal
        $dayCounts = collect();
        if ($audits->count()) {
            $dates = $audits->getCollection()->map(fn($audit) => $audit->created_at->toDateString());
            $dayCounts = $this->fastcrudAuditRepository->getAll([], $request)
                ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
                ->whereDate('created_at', '>=', $dates->min())
                ->whereDate('created_at', '<=', $dates->max())
                ->groupByRaw('DATE(created_at)')
                ->pluck('c', 'd');
        }

        $request->flash();
        return view('fastcrud::fastcrud_audit.index', compact('audits', 'dayCounts'));
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
