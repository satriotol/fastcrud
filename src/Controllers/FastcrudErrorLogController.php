<?php

namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Satriotol\Fastcrud\Repositories\FastcrudErrorLogRepository;

class FastcrudErrorLogController extends Controller
{
    protected $fastcrud_error_logRepository;

    public function __construct()
    {
        $this->fastcrud_error_logRepository = new FastcrudErrorLogRepository();

        $this->middleware('role:SUPERADMIN');
    }

    public function index(Request $request)
    {
        $fastcrud_error_logs = $this->fastcrud_error_logRepository->getAll(
            [],
            $request
        )->latest()->paginate();
        $request->flash();
        return view('backend.fastcrud_error_log.index', compact('fastcrud_error_logs'));
    }

    public function create()
    {
        return view('backend.fastcrud_error_log.create');
    }

    public function store(Request $request)
    {
        $validation = $this->fastcrud_error_logRepository->validate();
        $data = $request->validate($validation['rules'], $validation['messages']);
        DB::beginTransaction();
        try {
            $this->fastcrud_error_logRepository->create($data);
            DB::commit();
            return redirect(route('fastcrud_error_log.index'))->with('success', 'Fastcrud Beta Mode Berhasil Tersimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function show($uuid)
    {
        $fastcrud_error_log = $this->fastcrud_error_logRepository->findByUuid($uuid);
        return view('backend.fastcrud_error_log.create', compact('fastcrud_error_log'));
    }

    public function edit($uuid)
    {
        $fastcrud_error_log = $this->fastcrud_error_logRepository->findByUuid($uuid);
        return view('backend.fastcrud_error_log.create', compact('fastcrud_error_log'));
    }

    public function update(Request $request, $uuid)
    {
        $model = $this->fastcrud_error_logRepository->findByUuid($uuid);
        $validation = $this->fastcrud_error_logRepository->validate(true, $model->id);
        $data = $request->validate($validation['rules'], $validation['messages']);
        DB::beginTransaction();
        try {
            $this->fastcrud_error_logRepository->update($uuid, $data);
            DB::commit();
            return redirect(route('fastcrud_error_log.index'))->with('success', 'Fastcrud Beta Mode Berhasil Terupdate');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy($uuid)
    {
        DB::beginTransaction();
        try {
            $this->fastcrud_error_logRepository->delete($uuid);
            DB::commit();
            return back()->with('success', 'Fastcrud Beta Mode Berhasil Dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
