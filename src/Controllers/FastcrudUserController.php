<?php

namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Opd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class FastcrudUserController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function __construct()
  {
    $this->middleware('role:SUPERADMIN');
  }
  public function index(Request $request)
  {
    $name = $request->name;
    $must_change_password = $request->must_change_password;
    $users = User::getUsers();
    if ($name) {
      $users->where('name', 'LIKE', '%' . $name . '%');
    }
    if (isset($must_change_password)) {
      $users->where('must_change_password', (bool) $must_change_password);
    }
    $roles = Role::all();
    $users = $users->latest()->paginate();
    $request->flash();
    return view('fastcrud::fastcrud_user.index', compact('users', 'roles'));
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
    return view('fastcrud::fastcrud_user.create', compact('roles'));
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
    return redirect(route('fastcrud_user.index'));
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
  public function edit($uuid)
  {
    $user = User::where('uuid', $uuid)->first();
    if (Auth::user()->getRole()->name == 'SUPERADMIN') {
      $roles = Role::all();
    } else {
      $roles = Role::where('name', '!=', 'SUPERADMIN')->get();
    }
    return view('fastcrud::fastcrud_user.create', compact('user', 'roles'));
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
      'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:512'],
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
    if ($request->file('image')) {

      if ($user->image) {
        Storage::delete('public/' . $user->image);
      }

      $image = $request->file('image');
      $imageExtension = $image->getClientOriginalExtension();
      $imageName = 'user/' . date('mdYHis') . '-' . Str::random(8) . '.' . $imageExtension;
      $image->storeAs('public', $imageName);
      $userData['image'] = $imageName;
    }

    $user->update($userData);

    return $validatedData;
  }

  public function update(Request $request, User $user)
  {
    $validatedData = $this->updateUserData($request, $user, true); // Enable role validation

    // Sync roles if provided
    if (isset($validatedData['role'])) {
      $user->syncRoles($validatedData['role']);
    }

    session()->flash('success', 'Pengguna berhasil diperbarui');
    return redirect(route('fastcrud_user.index'));
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
