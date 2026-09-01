@extends('layouts/layoutMaster')

@section('title', ' CRUD')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/autosize/autosize.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/forms-extras.js') }}"></script>
    @include('partials.success')
@endsection

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Crud</h4>

    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info">
                <h6 class="mb-1">Panduan Singkat</h6>
                <p class="mb-0">Isi formulir ini untuk membuat konfigurasi CRUD otomatis. Beberapa tips:
                <ul class="mb-0 mt-2">
                    <li><strong>Nama Model</strong>: Gunakan nama model singular, mis. <code>UserProfile</code>.</li>
                    <li><strong>Nama Tabel</strong>: Gunakan nama tabel database (plural) sesuai migrasi, mis.
                        <code>user_profiles</code>.
                    </li>
                    <li><strong>Singular</strong>: Label tunggal yang akan tampil di UI, mis. <code>Pengguna</code>.</li>
                    <li><strong>Sidebar Logo</strong>: Nama ikon dari <a href="https://tabler.io/icons"
                            target="_blank">Tabler Icons</a>, mis. <code>device-imac</code>.</li>
                    <li>Tambahkan kolom di bagian bawah. Pilih tipe kolom, apakah file, dan apakah nullable.</li>
                </ul>
                </p>
            </div>
        </div>
    </div>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Crud</h5>
                </div>
                <div class="card-body">
                    <form
                        action="@isset($crud) {{ route('crud.update', $crud->id) }} @endisset @empty($crud) {{ route('crud.store') }} @endempty"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($crud)
                            @method('PUT')
                        @endisset
                        <div class="row mb-3">
                            @include('partials.errors')
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="model">Nama Model</label>
                            <div class="col-sm-10">
                                {{ html()->text('model', isset($crud) ? $crud->model : @old('model'))->class('form-control')->placeholder('Masukkan Nama Model')->required(true) }}
                                <small class="text-muted">Contoh: <code>UserProfile</code> — tanpa namespace. Sistem akan
                                    men-generate controller, view, dan route berdasarkan nama ini.</small>
                                @error('model')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="indonesian_name">Nama Indonesia</label>
                            <div class="col-sm-10">
                                {{ html()->text('indonesian_name', isset($crud) ? $crud->indonesian_name : @old('indonesian_name'))->class('form-control')->placeholder('Masukkan Nama Indonesia')->required(true) }}
                                <small class="text-muted">Contoh: <code>Profil Pengguna</code></small>
                                @error('indonesian_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="table">Nama Tabel</label>
                            <div class="col-sm-10">
                                {{ html()->text('table', isset($crud) ? $crud->table : @old('table'))->class('form-control')->placeholder('Masukkan Nama Table')->required(true) }}
                                <small class="text-muted">Contoh: <code>user_profiles</code>. Pastikan tabel sudah ada atau
                                    nanti jalankan migrasi.</small>
                                @error('table')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="singular">Singular</label>
                            <div class="col-sm-10">
                                {{ html()->text('singular', isset($crud) ? $crud->singular : @old('singular'))->class('form-control')->placeholder('Masukkan Nama Singular')->required(true) }}
                                <small class="text-muted">Nama satuan/ singular untuk resource, mis.
                                    <code>user_profile</code> — biasanya digunakan untuk route/identifier dan label
                                    tunggal.</small>

                                @error('singular')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="sidebarLogo">Sidebar Logo</label>
                            <div class="col-sm-10">
                                {{ html()->text('sidebarLogo', isset($crud) ? $crud->sidebarLogo : @old('sidebarLogo'))->class('form-control')->placeholder('device-imac')->required(true) }}
                                <a href="https://tabler.io/icons" target="_blank">Tabler.io</a>
                                @error('sidebarLogo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="soft_delete">Soft Delete</label>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    {{ html()->hidden('soft_delete', 0) }}
                                    {{ html()->checkbox('soft_delete', old('soft_delete'), 1)->class('form-check-input')->id('soft_delete') }}
                                    <label class="form-check-label" for="soft_delete">Aktifkan soft delete</label>
                                </div>
                                <small class="text-muted">Jika dicentang, tabel mendapat kolom <code>deleted_at</code> dan
                                    data yang dihapus hanya diarsipkan (bisa dipulihkan), bukan dihapus permanen. File
                                    upload juga tidak ikut dihapus.</small>
                                @error('soft_delete')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-repeater">
                                <div data-repeater-list="columns">
                                    <div data-repeater-item>
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="form-repeater-1-3">Tabel</label>
                                                {{ html()->text('column_name', isset($crud) ? $crud->column_name : @old('column_name'))->class('form-control')->placeholder('Masukkan Nama Kolom')->required(true) }}
                                                {{ html()->text('column_name_view', isset($crud) ? $crud->column_name_view : @old('column_name_view'))->class('form-control')->placeholder('Masukkan Nama Kolom View')->required(true) }}

                                            </div>
                                            <div class="mb-3 col-md-2">
                                                <label class="form-label" for="form-repeater-1-3">Tipe</label>
                                                {{ html()->select('type', [$type])->class('form-select')->placeholder('Pilih Tipe Kolom')->required(true) }}
                                            </div>
                                            <div class="mb-3 col-md-2">
                                                <label class="form-label" for="form-repeater-1-3">Is File</label>
                                                {{ html()->select('is_file', [false => 'Tidak', true => 'Ya'])->class('form-select')->placeholder('Apakah Format Upload')->required(true) }}
                                                {{ html()->select('is_minio', [false => 'Tidak', true => 'Ya'])->class('form-select')->placeholder('Minio Storage')->required(true) }}
                                            </div>
                                            <div class="mb-3 col-md-2">
                                                <label class="form-label" for="form-repeater-1-3">Nullable</label>
                                                {{ html()->select('nullable', ['0' => 'Wajib', '1' => 'Tidak Wajib'])->class('form-select')->placeholder('Pilih Tipe Kolom')->required(true) }}
                                            </div>
                                            <div class="mb-3 col-md-2 d-flex align-items-center mb-0">
                                                <button class="btn btn-label-danger mt-4" data-repeater-delete
                                                    type="button">
                                                    <i class="ti ti-x ti-xs me-1"></i>
                                                    <span class="align-middle">Delete</span>
                                                </button>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <button class="btn btn-primary" data-repeater-create type="button">
                                        <i class="ti ti-plus me-1"></i>
                                        <span class="align-middle">Add</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title mb-2">Preview (Real-time)</h6>
                                        <p class="mb-1"><strong>Model:</strong> <span
                                                id="preview-model">{{ isset($crud) ? $crud->model : '' }}</span></p>
                                        <p class="mb-1"><strong>Table:</strong> <span
                                                id="preview-table">{{ isset($crud) ? $crud->table : '' }}</span></p>
                                        <p class="mb-1"><strong>Route Prefix:</strong> <span
                                                id="preview-route">/{{ isset($crud) ? strtolower($crud->table) : '' }}</span>
                                        </p>
                                        <p class="mb-0"><strong>Columns:</strong> <span id="preview-columns">0</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-end text-end">
                            <div class="col-sm-10">
                                <a href="{{ route('crud.index') }}" class="btn btn-warning">Kembali</a>
                                <button type="submit" class="btn btn-primary"
                                    onclick="return confirm('Apakah Anda yakin ingin menyimpan?')">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function() {
            function qs(sel, ctx) {
                return (ctx || document).querySelector(sel);
            }

            function qsa(sel, ctx) {
                return Array.prototype.slice.call((ctx || document).querySelectorAll(sel));
            }

            var modelInput = qs('input[name="model"]');
            var tableInput = qs('input[name="table"]');
            var previewModel = qs('#preview-model');
            var previewTable = qs('#preview-table');
            var previewRoute = qs('#preview-route');
            var previewColumns = qs('#preview-columns');

            function updatePreview() {
                if (previewModel) previewModel.textContent = modelInput ? modelInput.value : '';
                if (previewTable) previewTable.textContent = tableInput ? tableInput.value : '';
                if (previewRoute) previewRoute.textContent = '/' + (tableInput && tableInput.value ? tableInput.value
                    .toLowerCase() : '');
                // Count repeater items (assumes jquery-repeater populates data-repeater-item)
                var items = qsa('[data-repeater-list] > [data-repeater-item]');
                if (previewColumns) previewColumns.textContent = items.length;
            }

            // Initial update
            document.addEventListener('DOMContentLoaded', function() {
                // Listen for native input changes
                if (modelInput) modelInput.addEventListener('input', updatePreview);
                if (tableInput) tableInput.addEventListener('input', updatePreview);

                // Also handle dynamic repeater: observe mutations to data-repeater-list
                var list = qs('[data-repeater-list]');
                if (list && window.MutationObserver) {
                    var mo = new MutationObserver(function() {
                        updatePreview();
                    });
                    mo.observe(list, {
                        childList: true,
                        subtree: false
                    });
                }

                // Try to update every 500ms for environments where repeater inserts later
                setInterval(updatePreview, 500);

                updatePreview();
            });
        })();
    </script>
@endsection
