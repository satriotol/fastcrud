<?php

namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
            'protected' => $this->protectedRouteCount(),
        ]);
    }

    /**
     * Middleware 'maintenance' bersifat opt-in, jadi tanpa hitungan ini superadmin
     * tidak punya cara tahu bahwa menyalakan maintenance belum memblokir apa pun.
     * gatherMiddleware() sudah mencakup middleware warisan dari route group.
     */
    private function protectedRouteCount(): int
    {
        return collect(Route::getRoutes())
            ->filter(fn($route) => in_array('maintenance', $route->gatherMiddleware(), true))
            ->count();
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
