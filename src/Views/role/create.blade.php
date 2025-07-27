@extends('layouts/layoutMaster')

@section('title', 'Assign Role - Permissions')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-icons/bootstrap-icons.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 plugin
            $('.select2').select2();

            // Handle group checkbox changes
            $('.check-all').on('change', function() {
                var isChecked = $(this).prop('checked');
                var prefix = $(this).data('prefix');

                // Toggle all checkboxes in the group
                $('input[name="permissions[]"]').each(function() {
                    if ($(this).data('prefix') === prefix) {
                        $(this).prop('checked', isChecked);
                    }
                });
            });

            // Handle global check all and clear all
            $('#checkAll').on('click', function() {
                $('input[name="permissions[]"]').prop('checked', true);
            });

            $('#clearAll').on('click', function() {
                $('input[name="permissions[]"]').prop('checked', false);
            });
        });
    </script>
@endsection


@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light"><i class="bi bi-person-badge me-1"></i> Manajemen Role /</span> Atur Hak Akses Role
    </h4>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
                    <h5 class="mb-0 text-white"><i class="bi bi-shield-lock me-2"></i> Atur Hak Akses Role</h5>
                    <div>
                        <button type="button" id="checkAll" class="btn btn-sm btn-success me-2"><i class="bi bi-check2-all me-1"></i>Centang Semua</button>
                        <button type="button" id="clearAll" class="btn btn-sm btn-outline-light"><i class="bi bi-x-circle me-1"></i>Bersihkan</button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-info-circle me-2 fs-5"></i>
                        <div>
                            <strong>Tips:</strong> Centang hak akses sesuai kebutuhan role. Gunakan tombol <b>Centang Semua</b> atau <b>Bersihkan</b> untuk memudahkan pengaturan. Anda dapat memilih per grup atau satu per satu.
                        </div>
                    </div>
                    <form
                        action="@isset($role) {{ route('role.update', $role->id) }} @endisset @empty($role) {{ route('role.store') }} @endempty"
                        method="POST" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @isset($role)
                            @method('PUT')
                        @endisset

                        <!-- Role Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Nama Role <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="name" name="name"
                                placeholder="Contoh: Admin, Operator, Warga" value="{{ isset($role) ? $role->name : old('name') }}" required autofocus>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Permissions -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Hak Akses (Permissions) <span class="text-danger">*</span></label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 22%;">
                                                <i class="bi bi-diagram-3 me-1"></i> Grup
                                            </th>
                                            <th scope="col">
                                                <i class="bi bi-list-check me-1"></i> Daftar Hak Akses
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissionsGrouped as $groupedPrefix => $permissions)
                                            <tr>
                                                <td class="bg-light">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input check-all" data-prefix="{{ $groupedPrefix }}" id="group-{{ $groupedPrefix }}">
                                                        <label class="form-check-label ms-2 fw-semibold text-primary" for="group-{{ $groupedPrefix }}">
                                                            {{ ucfirst($groupedPrefix) }}
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach ($permissions as $permission)
                                                            <div class="form-check me-3 mb-2">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="permission{{ $permission->id }}" name="permissions[]"
                                                                    value="{{ $permission->name }}"
                                                                    data-prefix="{{ $groupedPrefix }}"
                                                                    @isset($role) {{ $role->permissions->contains($permission) ? 'checked' : '' }} @endisset>
                                                                <label class="form-check-label" for="permission{{ $permission->id }}">
                                                                    {{ $permission->name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @error('permissions')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('role.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
                            <button type="submit" class="btn btn-primary btn-lg px-4"><i class="bi bi-save me-1"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        .select2-container .select2-selection--single {
            height: 44px !important;
            padding: 8px 12px;
            font-size: 1rem;
        }
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .form-check-label {
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .card-body { padding: 1.2rem; }
            .table-responsive { font-size: 0.97rem; }
        }
    </style>
@endsection