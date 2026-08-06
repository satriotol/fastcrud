<?php

namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Satriotol\Fastcrud\Exceptions\FastcrudException;
use Satriotol\Fastcrud\Models\FastcrudRegistration;
use Satriotol\Fastcrud\Repositories\FastcrudRegistrationRepository;
use Satriotol\Fastcrud\Repositories\FastcrudRoleRepository;

class FastcrudRegistrationController extends Controller
{
    protected $fastcrudRegistrationRepository;
    protected $fastcrudRoleRepository;

    public function __construct()
    {
        $this->fastcrudRegistrationRepository = new FastcrudRegistrationRepository();
        $this->fastcrudRoleRepository = new FastcrudRoleRepository();
        $this->middleware('auth')->except(['form', 'submit']);
        $this->middleware('permission:fastcrud_registration-index')->only(['index']);
        $this->middleware('permission:fastcrud_registration-approve')->only(['approve', 'reject']);
        $this->middleware('permission:fastcrud_registration-delete')->only(['destroy']);
    }

    /**
     * Form pendaftaran publik, hanya bisa diakses lewat link ber-token.
     */
    public function form($token)
    {
        $this->assertToken($token);

        return view('fastcrud::fastcrud_registration.register', [
            'token' => $token,
            'pageConfigs' => ['myLayout' => 'blank'],
        ]);
    }

    public function submit(Request $request, $token)
    {
        $this->assertToken($token);

        $validate = $this->fastcrudRegistrationRepository->validateRegister();
        $data = $request->validate($validate['rules'], $validate['messages']);

        try {
            DB::beginTransaction();
            $this->fastcrudRegistrationRepository->create($data);
            DB::commit();

            return redirect(route('login'))
                ->with('success', 'Pendaftaran terkirim. Akun akan aktif setelah disetujui admin.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());

            return back()->with('error', 'Pendaftaran gagal diproses.')->withInput();
        }
    }

    public function index(Request $request)
    {
        $registrations = $this->fastcrudRegistrationRepository->getAll([], $request)->latest()->paginate(10);
        $roles = $this->fastcrudRoleRepository->search()->get();
        $counts = FastcrudRegistration::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
        $request->flash();

        return view('fastcrud::fastcrud_registration.index', compact('registrations', 'roles', 'counts'));
    }

    public function approve(Request $request, $uuid)
    {
        $data = $request->validate([
            'role' => 'required|array',
            'role.*' => 'exists:roles,name',
        ], [
            'role.required' => 'Role harus dipilih.',
        ]);

        try {
            DB::beginTransaction();
            $this->fastcrudRegistrationRepository->approve($uuid, $data['role']);
            DB::commit();

            return back()->with('success', 'Pendaftaran disetujui, pengguna berhasil dibuat.');
        } catch (FastcrudException $fe) {
            DB::rollBack();

            return back()->with('error', $fe->getMessage());
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());

            return back()->with('error', 'Pendaftaran gagal disetujui.');
        }
    }

    public function reject(Request $request, $uuid)
    {
        $data = $request->validate([
            'reject_reason' => 'nullable|string|max:255',
        ]);

        try {
            $this->fastcrudRegistrationRepository->reject($uuid, $data['reject_reason'] ?? null);

            return back()->with('success', 'Pendaftaran ditolak.');
        } catch (FastcrudException $fe) {
            return back()->with('error', $fe->getMessage());
        }
    }

    public function destroy($uuid)
    {
        $this->fastcrudRegistrationRepository->delete($uuid);

        return back()->with('success', 'Pendaftaran berhasil dihapus.');
    }

    private function assertToken($token)
    {
        abort_unless(hash_equals(FastcrudRegistration::linkToken(), (string) $token), 404);
    }
}
