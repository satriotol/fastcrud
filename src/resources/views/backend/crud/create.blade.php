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
@endsection

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Crud</h4>

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
                                @error('model')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="table">Nama Tabel</label>
                            <div class="col-sm-10">
                                {{ html()->text('table', isset($crud) ? $crud->table : @old('table'))->class('form-control')->placeholder('Masukkan Nama Table')->required(true) }}
                                @error('table')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="singular">Singular</label>
                            <div class="col-sm-10">
                                {{ html()->text('singular', isset($crud) ? $crud->singular : @old('singular'))->class('form-control')->placeholder('Masukkan Nama Singular')->required(true) }}

                                @error('singular')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="sidebarLogo">Sidebar Logo</label>
                            <div class="col-sm-10">
                                {{ html()->text('sidebarLogo', isset($crud) ? $crud->sidebarLogo : @old('sidebarLogo'))->class('form-control')->placeholder('far fa-window-restore')->required(true) }}
                                <a href="https://fontawesome.com/v5/search?o=r&m=free&s=regular" target="_blank">Font
                                    Awesome</a>
                                @error('sidebarLogo')
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

                                            </div>
                                            <div class="mb-3 col-md-2">
                                                <label class="form-label" for="form-repeater-1-3">Nullable</label>
                                                {{ html()->select('nullable', ['0' => 'Wajib', 'nullable' => 'Tidak Wajib'])->class('form-select')->placeholder('Pilih Tipe Kolom')->required(true) }}


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
@endsection