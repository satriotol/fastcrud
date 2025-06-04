<?php

namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Satriotol\Fastcrud\Models\FastcrudUser;
use Satriotol\Fastcrud\Repositories\FastcrudRoleRepository;
use Satriotol\Fastcrud\Repositories\FastcrudUserRepository;

class FastcrudUserController extends Controller
{
  protected $fastcrudUserRepository;
  protected $fastcrudRoleRepository;
  public function __construct()
  {
    $this->fastcrudUserRepository = new FastcrudUserRepository();
    $this->fastcrudRoleRepository = new FastcrudRoleRepository();
    $this->middleware('role:SUPERADMIN');
  }
  public function index(Request $request)
  {
    $users = $this->fastcrudUserRepository->getAll([], $request)->latest()->paginate(10);
    $users_counts = $this->fastcrudUserRepository->getAll([], $request)->count();
    $roles = $this->fastcrudRoleRepository->getAll([], $request)->get();
    $request->flash();
    return view('fastcrud::fastcrud_user.index', compact('users', 'roles', 'users_counts'));
  }
  public function setMustChangePassword($uuid)
  {
    $user = $this->fastcrudUserRepository->findByUuid($uuid);
    $user->update([
      'must_change_password' => !$user->must_change_password,
    ]);
    session()->flash('success', 'Password berhasil diubah');
    return back();
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(Request $request)
  {
    $roles = $this->fastcrudRoleRepository->getAll([], $request)->get();
    return view('fastcrud::fastcrud_user.create', compact('roles'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validate = $this->fastcrudUserRepository->validate();
    $data = $request->validate($validate['rules'], $validate['messages']);
    $this->fastcrudUserRepository->create($data);
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
  public function reset2Fa($uuid){
    $fastcrud_user = $this->fastcrudUserRepository->findByUuid($uuid);
    $fastcrud_user->google2fa_secret = null;
    $fastcrud_user->google2fa_verified = false;
    $fastcrud_user->save();
    session()->flash('success', '2FA berhasil direset');
    return back();
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($uuid, Request $request)
  {
    $user = $this->fastcrudUserRepository->findByUuid($uuid);
    $roles = $this->fastcrudRoleRepository->getAll([], $request)->get();
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

  public function update(Request $request, $id)
  {
    $user = User::find($id);
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
  public function destroy($id)
  {
    $user = User::find($id);
    $user->delete();
    session()->flash('success', 'Pengguna Berhasil Dihapus');

    return back();
  }
}
