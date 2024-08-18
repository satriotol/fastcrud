@extends('layouts/layoutMaster')

@section('title', 'Password Form')

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
@endsection

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Reset Password</h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Reset Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('password.change') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($user)
                            @method('PUT')
                        @endisset
                        <div class="row mb-3">
                            @include('partials.errors')
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
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
