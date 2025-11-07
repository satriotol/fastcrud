@extends('layouts/layoutMaster')

@section('title', 'User List - Pages')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
@endsection

@section('page-script')
    @include('partials.success')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables untuk setiap modal
            $('table[id^="datatableRole"]').each(function() {
                $(this).DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true
                });
            });

            // Re-initialize DataTables jika modal dibuka kembali
            $('div.modal').on('shown.bs.modal', function() {
                var table = $(this).find('table').DataTable();
                table.columns.adjust().draw();
            });
        });
    </script>
@endsection

@section('content')
    <div class="card shadow-lg border-0">
        <div class="card-header border-bottom bg-primary text-white rounded-top">
            <div class="row align-items-center">
                <div class="col-md-6 col-12 mb-2 mb-md-0">
                    <h5 class="card-title mb-0 text-white"><i class="bi bi-people-fill me-2"></i> Daftar Role & Hak Akses
                    </h5>
                </div>
                <div class="col-md-6 col-12 text-md-end text-start">
                    <a href="{{ route('role.create') }}" class="btn btn-success btn-sm px-3">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Role
                    </a>
                </div>
            </div>
            <form action="" class="mt-3">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4 col-12">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                            {{ html()->text('name')->class('form-control')->placeholder('Cari Nama Role')->value(@old('name')) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-light btn-sm" type="submit">
                            <i class="ti ti-search me-1"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-info-circle me-2 fs-5"></i>
                <div>
                    <strong>Tips:</strong> Klik jumlah user untuk melihat detail anggota role. Gunakan tombol <b>Tambah
                        Role</b> untuk menambah role baru.
                </div>
                <a href="{{ route('role.view') }}">Download Json</a>
            </div>
            @include('partials.success')
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;"><i class="bi bi-hash"></i> No</th>
                            <th><i class="bi bi-person-badge"></i> Nama Role</th>
                            <th style="width: 12%;"><i class="bi bi-people"></i> User</th>
                            <th style="width: 10%;"><i class="bi bi-gear"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = ($roles->currentPage() - 1) * $roles->perPage() + 1;
                        @endphp
                        @foreach ($roles as $role)
                            <tr>
                                <td class="text-center fw-bold">{{ $no++ }}</td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fs-6">
                                        <i class="bi bi-person-badge me-1"></i> {{ $role->name }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-primary btn-sm px-2 py-1"
                                        data-bs-toggle="modal" data-bs-target="#modalRole{{ $role->id }}"
                                        title="Lihat anggota role">
                                        <i class="bi bi-people-fill me-1"></i> {{ $role->users->count() }}
                                    </button>
                                    <div class="modal fade" id="modalRole{{ $role->id }}" tabindex="-1"
                                        aria-labelledby="modalRoleLabel{{ $role->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title" id="modalRoleLabel{{ $role->id }}">
                                                        <i class="bi bi-people me-2"></i> Anggota Role: <span
                                                            class="fw-bold">{{ $role->name }}</span>
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-striped"
                                                        id="datatableRole{{ $role->id }}">
                                                        <thead>
                                                            <tr>
                                                                <th><i class="bi bi-person"></i> User</th>
                                                                <th><i class="bi bi-key"></i> Reset Password</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($role->users as $user)
                                                                <tr>
                                                                    <td><i class="bi bi-person-circle me-1"></i>
                                                                        {{ $user->name }}</td>
                                                                    <td>
                                                                        @if ($user->must_change_password)
                                                                            <span class="badge bg-danger"><i
                                                                                    class="bi bi-x-circle"></i> Harus
                                                                                Reset</span>
                                                                        @else
                                                                            <span class="badge bg-success"><i
                                                                                    class="bi bi-check-circle"></i>
                                                                                Aman</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('role.edit', $role->id) }}"><i
                                                    class="ti ti-pencil me-1"></i>
                                                Edit</a>
                                            <form action="{{ route('role.destroy', $role->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="dropdown-item"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                    <i class="ti ti-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $roles->appends($_GET)->links('pagination::bootstrap-5')->withClass('pagination-container') }}
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
