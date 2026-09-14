<?php

namespace Satriotol\Fastcrud\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use OwenIt\Auditing\Models\Audit;
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
}
