<?php

namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function index()
    {
        $audits = Audit::latest()->paginate();
        return view('backend.audit.index', compact('audits'));
    }
}
