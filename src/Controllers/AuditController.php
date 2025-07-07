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
        $this->middleware('role:SUPERADMIN');
    }

    public function index(Request $request)
    {
        $audits = $this->fastcrudAuditRepository->getAll([], $request)->latest()->paginate();
        $request->flash();
        return view('fastcrud::fastcrud_audit.index', compact('audits'));
    }
}
