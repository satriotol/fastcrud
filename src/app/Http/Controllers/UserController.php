<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function __construct()
  {
    $this->middleware('permission:user-index|user-create|user-show|user-edit|user-delete', ['only' => ['index', 'show']]);
    $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
    $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
    $this->middleware('permission:user-delete', ['only' => ['destroy']]);
  }
  public function index(Request $request)
  {
    $name = $request->name;
    $users = User::getUsers();
    if ($name) {
      $users->where('name', 'LIKE', '%' . $name . '%');
    }
    $users = $users->latest()->paginate();
    $request->flash();
    return view('backend.user.index', compact('users'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    if (Auth::user()->getRole()->name == 'SUPERADMIN') {
      $roles = Role::all();
    } else {
      $roles = Role::where('name', '!=', 'SUPERADMIN')->get();
    }
    return view('backend.user.create', compact('roles'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $data = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
      'password' => ['required', 'confirmed', Password::defaults()],
      'role' => ['required'],
    ]);
    $user = User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
    ]);
    $user->assignRole($data['role']);

    session()->flash('success', 'Pengguna Berhasil Dibuat');
    return redirect(route('user.index'));
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function profile()
  {
    $user = Auth::user();
    if (Auth::user()->getRole()->name == 'SUPERADMIN') {
      $roles = Role::all();
    } else {
      $roles = Role::where('name', '!=', 'SUPERADMIN')->get();
    }
    return view('backend.user.create', compact('user', 'roles'));
  }
  public function edit($uuid)
  {
    $user = User::where('uuid', $uuid)->first();
    if (Auth::user()->getRole()->name == 'SUPERADMIN') {
      $roles = Role::all();
    } else {
      $roles = Role::where('name', '!=', 'SUPERADMIN')->get();
    }
    return view('backend.user.create', compact('user', 'roles'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, User $user)
  {
    $data = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
      'password' => ['nullable', 'confirmed', Password::defaults()],
      'role' => ['required'],
    ]);

    $userData = [
      'name' => $data['name'],
      'email' => $data['email'],
    ];

    // Periksa apakah password yang diberikan tidak null sebelum mengupdate password
    if ($data['password'] !== null) {
      $userData['password'] = Hash::make($data['password']);
    }
    $user->update($userData);
    $user->syncRoles($data['role']);
    session()->flash('success', 'Pengguna Berhasil Diupdate');
    if ($request->profile) {
      return back();
    }
    return redirect(route('user.index'));
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(User $user)
  {
    $user->delete();
    session()->flash('success', 'Pengguna Berhasil Dihapus');

    return back();
  }
}
