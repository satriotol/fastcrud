<?php

namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Satriotol\Fastcrud\Middleware\MaintenanceMode;

class MaintenanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:SUPERADMIN');
    }

    public function index()
    {
        return view('fastcrud::maintenance.index', [
            'active' => MaintenanceMode::active(),
            'message' => MaintenanceMode::active() ? MaintenanceMode::message() : '',
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'active' => 'nullable|boolean',
            'message' => 'nullable|string|max:500',
        ]);

        $file = MaintenanceMode::file();

        if ($request->boolean('active')) {
            file_put_contents($file, (string) $request->input('message'));
            $status = 'Mode maintenance dinyalakan. Hanya SUPERADMIN yang bisa mengakses aplikasi.';
        } else {
            if (is_file($file)) {
                unlink($file);
            }
            $status = 'Mode maintenance dimatikan. Aplikasi kembali normal.';
        }

        return back()->with('success', $status);
    }
}
