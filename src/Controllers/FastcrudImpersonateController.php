<?php

namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class FastcrudImpersonateController extends Controller
{
  public function __construct()
  {
    $this->middleware('role:SUPERADMIN|IMPERSONATE', ['only' => ['loginAs']]);
  }
  public function loginAs($id)
  {
    $user = User::findOrFail($id);

    // Simpan ID admin asli
    Session::put('admin_id', Auth::id());

    // Login sebagai user target
    Auth::login($user);

    return redirect()->route('dashboard.index')
      ->with('success', 'Login sebagai ' . $user->name);
  }

  public function loginBack()
  {
    $adminId = Session::pull('admin_id');

    if ($adminId && $admin = User::find($adminId)) {
      Auth::login($admin);

      return redirect()->route('fastcrud_user.index')
        ->with('success', 'Kembali sebagai admin');
    }

    return redirect()->route('dashboard.index')
      ->with('error', 'Gagal kembali ke akun admin.');
  }
}
