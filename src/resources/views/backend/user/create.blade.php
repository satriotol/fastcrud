@extends('layouts/layoutMaster')

@section('title', ' Horizontal Layouts - Forms')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="https://cdn.ckeditor.com/4.17.0/full/ckeditor.js"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <script>
        // Inisialisasi CKEditor 4 untuk semua elemen dengan kelas "ckeditor"
        CKEDITOR.replace('editor', {
            toolbar: 'Basic'
        });
    </script>
@endsection

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> {Pengguna}</h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{Pengguna}</h5>
                </div>
                <div class="card-body">
                    <form
                        action="@isset($user) {{ route('user.update', $user->id) }} @endisset @empty($user) {{ route('user.store') }} @endempty"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($user)
                            @method('PUT')
                        @endisset
                        <div class="row mb-3">
                            {{ html()->label('Nama')->class('col-sm-2 col-form-label')->for('name') }}
                            <div class="col-sm-10">
                                {{ html()->text('name')->class('form-control')->placeholder('Masukkan Nama')->value(isset($user) ? $user->name : @old('name'))->required() }}
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            {{ html()->label('E-mail')->class('col-sm-2 col-form-label')->for('name') }}
                            <div class="col-sm-10">
                                {{ html()->email('email')->class('form-control')->placeholder('Masukkan E-mail')->value(isset($user) ? $user->email : @old('email'))->required() }}
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="role">Role</label>
                            <div class="col-sm-10">
                                {!! Form::select('role', $roles->pluck('name', 'name'), isset($user) ? $user->getRole()->name : '', [
                                    'class' => 'form-control select2',
                                    'required',
                                    'placeholder' => 'Pilih Role',
                                ]) !!}
                                @error('role')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row form-password-toggle mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-password">Password</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    {{ html()->password('password')->placeholder('Masukkan Password')->id('multicol-password')->class('form-control')->required(isset($user) ? false : true) }}
                                    <span class="input-group-text cursor-pointer" id="multicol-password2"><i
                                            class="ti ti-eye-off"></i></span>
                                </div>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row form-password-toggle mb-3">
                            <label class="col-sm-2 col-form-label" for="multicol-password">Konfirmasi Password</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    {{ html()->password('password_confirmation')->placeholder('Masukkan Konfirmasi Password')->id('multicol-password')->class('form-control')->required(isset($user) ? false : true) }}
                                    <span class="input-group-text cursor-pointer" id="multicol-password2"><i
                                            class="ti ti-eye-off"></i></span>
                                </div>
                                @error('password_confirmation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end text-end">
                            <div class="col-sm-10">
                                <a href="{{ route('user.index') }}" class="btn btn-warning">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
