@extends('layouts/layoutMaster')

@section('title', 'User List - Pages')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
@endsection

@section('page-script')
    @include('partials.success')
@endsection

@section('content')
    <div class="row g-3 align-items-stretch mb-3">
        <div class="col-lg-8 col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white rounded-top">
                    <i class="ti ti-search me-2"></i> Pencarian Pengguna
                </div>
                <div class="card-body">
                    <form action="">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i class="ti ti-user me-1"></i> Nama</label>
                                {{ html()->text('name')->class('form-control')->placeholder('Cari Nama')->value(@old('name')) }}
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i class="ti ti-mail me-1"></i> E-mail</label>
                                {{ html()->text('email')->class('form-control')->placeholder('Cari E-mail')->value(@old('email')) }}
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i class="ti ti-lock me-1"></i> Status Reset
                                    Password</label>
                                {{ html()->select('must_change_password', ['1' => 'Belum', '0' => 'Sudah'])->class('form-select')->placeholder('Pilih Status Reset Password')->value(@old('must_change_password')) }}
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i class="ti ti-shield me-1"></i> Role</label>
                                {{ html()->select('role', $roles->pluck('name', 'name'))->class('form-select')->placeholder('Pilih Role')->value(@old('role')) }}
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button class="btn btn-primary btn-sm px-4" type="submit">
                                <i class="ti ti-search me-1"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <div class="d-flex align-items-center mb-2">
                        <span class="fs-2 text-primary"><i class="ti ti-users"></i></span>
                        <span class="ms-2 fs-4 fw-bold">{{ $users_counts }}</span>
                    </div>
                    <div class="text-muted mb-1">Total Pengguna</div>
                    <div class="d-flex align-items-center">
                        <span class="fs-5 text-success"><i class="ti ti-shield-check"></i></span>
                        <span class="ms-2 fs-6 fw-semibold">{{ $roles->count() }} Role</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white rounded-top d-flex justify-content-between align-items-center">
            <div>
                <i class="ti ti-users me-2"></i> Daftar Pengguna
            </div>
            @can('user-create')
                <div>
                    @can('fastcrud_user_reset_password-multiple')
                        <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            <i class="ti ti-lock me-1"></i> Reset Password
                        </button>
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <form action="{{ route('users.resetPasswords') }}" method="post">
                                @csrf
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="ti ti-lock"></i> Reset
                                                Password</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <div class="form-group">
                                                {{ html()->label('Role')->for('role') }}
                                                {{ html()->select('role', $roles->pluck('name', 'name'))->class('form-select')->placeholder('Pilih Role')->required(true) }}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endcan
                    <a href="{{ route('fastcrud_user.create') }}" class="btn btn-success btn-sm">
                        <i class="ti ti-plus me-1"></i> Tambah
                    </a>
                </div>
            @endcan
        </div>
        <div class="card-body">
            <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                <i class="ti ti-info-circle me-2 fs-5"></i>
                <div>
                    <strong>Tips:</strong> Klik ikon <i class="ti ti-user"></i> untuk impersonate, <i
                        class="ti ti-lock"></i> untuk reset password, dan <i class="ti ti-shield-check"></i> untuk melihat
                    role.
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th><i class="ti ti-hash"></i> No</th>
                            <th><i class="ti ti-id"></i> Id</th>
                            <th><i class="ti ti-user"></i> Nama</th>
                            <th><i class="ti ti-mail"></i> E-mail</th>
                            <th><i class="ti ti-shield-check"></i> Role</th>
                            <th><i class="ti ti-clock"></i> Aktivitas</th>
                            <th><i class="ti ti-lock"></i> Reset Password</th>
                            <th><i class="ti ti-shield"></i> 2FA</th>
                            <th><i class="ti ti-settings"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = ($users->currentPage() - 1) * $users->perPage() + 1;
                        @endphp
                        @foreach ($users as $user)
                            <tr>
                                <td class="text-center fw-bold">{{ $no++ }}</td>
                                <td class="text-center">{{ $user->id }}</td>
                                <td>
                                    <span class="fw-semibold"><i class="ti ti-user me-1"></i> {{ $user->name }}</span>
                                    <a href="{{ route('fastcrud_user.impersonate.login_as', $user->id) }}"
                                        class="btn btn-sm btn-outline-primary ms-2" title="Impersonate">
                                        <i class="ti ti-user-check"></i>
                                    </a>
                                </td>
                                <td>
                                    <span><i class="ti ti-mail me-1"></i> {{ $user->email }}</span><br>
                                    <small class="text-muted"><i class="ti ti-calendar me-1"></i>
                                        {{ $user->created_at }}</small>
                                </td>
                                <td>
                                    @foreach ($user->roles as $role)
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 mb-1">
                                            <i class="ti ti-shield-check me-1"></i> {{ $role->name }}
                                        </span><br>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <span><i class="ti ti-clock me-1"></i> {{ $user->last_used_sign_in_at }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('fastcrud_user.setMustChangePassword', $user->uuid) }}"
                                        class="btn btn-sm {{ $user->must_change_password ? 'btn-danger' : 'btn-success' }}"
                                        title="Reset Password">
                                        @if ($user->must_change_password)
                                            <i class="ti ti-lock-off"></i>
                                        @else
                                            <i class="ti ti-lock-check"></i>
                                        @endif
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('fastcrud_user.reset2Fa', $user->uuid) }}"
                                        class="btn btn-sm {{ $user->google2fa_secret ? 'btn-success' : 'btn-danger' }}"
                                        title="Reset 2FA">
                                        @if ($user->google2fa_secret)
                                            <i class="ti ti-shield-check"></i>
                                        @else
                                            <i class="ti ti-shield-x"></i>
                                        @endif
                                    </a>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-light btn-sm px-2 py-1 dropdown-toggle"
                                            data-bs-toggle="dropdown" title="Aksi"><i
                                                class="ti ti-settings"></i></button>
                                        <div class="dropdown-menu">
                                            @can('user-edit')
                                                <a class="dropdown-item"
                                                    href="{{ route('fastcrud_user.edit', $user->uuid) }}"><i
                                                        class="ti ti-pencil me-1"></i> Edit</a>
                                            @endcan
                                            @can('fastcrud_user_reset_password-single')
                                                <form action="{{ route('user.resetPassword', $user->uuid) }}" method="post">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Apakah Anda Reset Password Pengguna Ini?')">
                                                        <i class="ti ti-lock me-1"></i> Reset Password
                                                    </button>
                                                </form>
                                            @endcan
                                            @if (Auth::user()->id != $user->id)
                                                @can('user-delete')
                                                    <form action="{{ route('fastcrud_user.destroy', $user->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                            <i class="ti ti-trash me-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                @endcan
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->appends($_GET)->links('pagination::bootstrap-5')->withClass('pagination-container') }}
        </div>
    </div>
    <style>
        .badge.bg-primary.bg-opacity-10 {
            background-color: #e7f1ff !important;
            color: #0d6efd !important;
            font-weight: 500;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
        }

        .modal-header.bg-primary {
            background: linear-gradient(90deg, #0d6efd 60%, #4e9cff 100%);
        }

        .btn-outline-primary.btn-sm {
            font-size: 0.98rem;
        }

        .dropdown-menu .dropdown-item i {
            min-width: 18px;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1.2rem;
            }

            .table-responsive {
                font-size: 0.97rem;
            }
        }
    </style>
@endsection
