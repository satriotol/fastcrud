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
    $roles = Role::all();
    $users = $users->latest()->paginate();
    $request->flash();
    return view('backend.user.index', compact('users', 'roles'));
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
    return view('backend.user.profilecreate', compact('user'));
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
  private function updateUserData($request, $user, $requireRole = false)
  {
    $rules = [
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
      'password' => ['nullable', 'confirmed', Password::defaults()],
    ];

    // Add role validation if required
    if ($requireRole) {
      $rules['role'] = ['required', 'exists:roles,name']; // Validates that the role exists
    }

    $validatedData = $request->validate($rules);

    // Prepare data for update
    $userData = [
      'name' => $validatedData['name'],
      'email' => $validatedData['email'],
    ];

    // Hash password if provided
    if (!empty($validatedData['password'])) {
      $userData['password'] = Hash::make($validatedData['password']);
    }

    $user->update($userData);

    return $validatedData;
  }


  public function updateProfile(Request $request)
  {
    $user = Auth::user();
    $this->updateUserData($request, $user); // No role validation needed
    session()->flash('success', 'Profil berhasil diperbarui');
    return redirect(route('dashboard.index'));
  }

  public function update(Request $request, User $user)
  {
    $validatedData = $this->updateUserData($request, $user, true); // Enable role validation

    // Sync roles if provided
    if (isset($validatedData['role'])) {
      $user->syncRoles($validatedData['role']);
    }

    session()->flash('success', 'Pengguna berhasil diperbarui');
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
