@extends('layouts/layoutMaster')

@section('title', ' Role - Forms')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi plugin Select2 pada elemen dengan class 'select2'
            $('.select2').select2();

            // Handle perubahan pada elemen select dengan class 'prefix-select'
            $('.prefix-select').on('change', function() {
                var prefix = $(this).val(); // Dapatkan prefix yang dipilih
                var checkboxes = $('input[name="permissions[]"]'); // Semua checkbox permissions

                // Uncheck semua checkbox terlebih dahulu
                checkboxes.prop('checked', false);

                // Cek jika checkbox memiliki prefix yang sesuai dan ubah statusnya
                checkboxes.each(function() {
                    if ($(this).data('prefix') === prefix) {
                        $(this).prop('checked', true);
                    }
                });
            });

            // Handle klik pada elemen dengan class 'check-all'
            $('.check-all').on('change', function() {
                var isChecked = $(this).prop('checked'); // Dapatkan status checkbox 'check-all'
                var prefix = $(this).data('prefix'); // Dapatkan prefix yang sesuai

                // Cek semua checkbox dengan prefix yang sesuai
                $('input[name="permissions[]"]').each(function() {
                    if ($(this).data('prefix') === prefix) {
                        $(this).prop('checked', isChecked);
                    }
                });
            });
        });
    </script>
@endsection

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> {Role}</h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{Role}</h5>
                </div>
                <div class="card-body">
                    <form
                        action="@isset($role) {{ route('role.update', $role->id) }} @endisset @empty($role) {{ route('role.store') }} @endempty"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($role)
                            @method('PUT')
                        @endisset
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="name">Nama Role</label>
                            <div class="col-sm-10">
                                {{ html()->text('name', isset($role) ? $role->name : @old('name'))->class('form-control')->placeholder('Masukkan Nama Role')->required() }}
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Permission</label>
                            <div class="col-sm-10">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Group Prefix</th>
                                            <th>Permissions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissionsGrouped as $groupedPrefix => $permissions)
                                            <tr>
                                                <td>{{ $groupedPrefix }}</td>
                                                <td>
                                                    <div class="form-check">
                                                        <h6 class="mb-0">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input check-all" type="checkbox"
                                                                    data-prefix="{{ $groupedPrefix }}">{{ $groupedPrefix }}
                                                            </label>
                                                        </h6>
                                                    </div>
                                                    @foreach ($permissions as $permission)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="permissions[]"
                                                                id="checkPermission{{ $permission->id }}"
                                                                value="{{ $permission->name }}"
                                                                @isset($role) {{ $role->permissions->contains($permission) ? 'checked' : '' }} @endisset
                                                                data-prefix="{{ $groupedPrefix }}">
                                                            <label class="form-check-label"
                                                                for="checkPermission{{ $permission->id }}">{{ $permission->name }}</label>
                                                        </div>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @error('permissions')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end text-end">
                            <div class="col-sm-10">
                                <a href="{{ route('permission.index') }}" class="btn btn-warning">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
