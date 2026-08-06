@php
    $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Pendaftaran Akses')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
@endsection

@section('page-script')
    @include('partials.success')
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">

                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20, 'withbg' => 'fill: #fff;'])</span>
                                <span
                                    class="app-brand-text demo text-body fw-bold ms-1">{{ config('variables.templateName') }}</span>
                            </a>
                        </div>

                        <h4 class="mb-1 pt-2">Pengajuan Akses</h4>
                        <p class="mb-4">Isi data di bawah ini. Akun Anda akan aktif setelah disetujui admin.</p>

                        @include('partials.errors')

                        <form class="mb-3" action="{{ route('fastcrud_registration.submit', $token) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                {{ html()->label()->text('Nama')->class('form-label') }}
                                {{ html()->text('name')->id('name')->class('form-control')->placeholder('Masukkan Nama')->value(@old('name'))->required() }}
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                {{ html()->label()->text('Email')->class('form-label') }}
                                {{ html()->email('email')->id('email')->class('form-control')->placeholder('Masukkan Email')->value(@old('email'))->required() }}
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                {{ html()->label()->text('Password')->class('form-label') }}
                                {{ html()->password('password')->id('password')->class('form-control')->placeholder('Masukkan Password')->required() }}
                                <small class="text-muted">Minimal 8 karakter, mengandung huruf kecil, huruf besar, angka,
                                    dan simbol (@$!%*#?&_).</small>
                                @error('password')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                {{ html()->label()->text('Konfirmasi Password')->class('form-label') }}
                                {{ html()->password('password_confirmation')->id('password_confirmation')->class('form-control')->placeholder('Ulangi Password')->required() }}
                            </div>
                            <div class="mb-3">
                                {{ html()->label()->text('Keperluan Akses')->class('form-label') }}
                                {{ html()->textarea('note', @old('note'))->class('form-control')->rows(3)->placeholder('Contoh: Staf Bidang X, butuh akses untuk input data Y') }}
                                @error('note')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button class="btn btn-primary d-grid w-100" type="submit">
                                Kirim Pengajuan
                            </button>
                        </form>

                        <div class="alert alert-info d-flex align-items-center mb-0" role="alert">
                            <i class="ti ti-info-circle me-2 fs-5"></i>
                            <div>
                                Pengajuan Anda masuk ke antrian. Admin akan menentukan role dan mengaktifkan akun Anda.
                            </div>
                        </div>

                        <p class="text-center mt-3 mb-0">
                            <span>Sudah punya akun?</span>
                            <a href="{{ route('login') }}"><span>Masuk di sini</span></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
