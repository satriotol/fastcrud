<?php

namespace App\Http\Controllers;

use App\Models\Asistensi;
use App\Models\Konsultasi;
use App\Models\PermohonanInformasi;
use App\Models\UpgReport;
use App\Models\UserDetail;
use App\Models\WbsReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Spatie\Permission\Models\Role;


class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('backend.dashboard');
    }
    public function storeUserDetail(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'nullable',
            'phone' => 'required|numeric',
            'address' => 'required',
            'gender' => 'required',
            'jabatan' => 'nullable',
            'instansi' => 'nullable'
        ]);
        $user = Auth::user();
        $data['user_id'] = $user->id;
        DB::beginTransaction();
        try {
            $userDetail = UserDetail::create($data);
            DB::table('model_has_roles')->where('model_id', $user->id)->delete();
            $userDetail->user->assignRole('USER');
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
        }
        session()->flash('success');
        return back();
    }
}
