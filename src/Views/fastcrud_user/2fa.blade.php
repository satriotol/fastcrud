@extends('layouts/layoutMaster')

@section('title', '2FA Authentication')

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
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">2FA Authentication</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    <h5>Scan QR Code</h5>
                    @if ($google2fa_url !== null)
                        {!! $google2fa_url !!}
                    @else
                        <div class="alert alert-warning">
                            QR Code tidak tersedia. Kemungkinan akun Anda sudah tertaut pada perangkat lain.  
                            Jika Anda mengalami kesulitan, silakan hubungi administrator untuk mereset 2FA Anda.
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <p>Silakan scan QR Code dengan aplikasi Google Authenticator atau Authy, lalu masukkan kode OTP yang muncul.</p>
                    <form action="{{ route('2fa.verify') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="otp" class="form-label">Kode OTP</label>
                            <input type="text" id="otp" name="otp" class="form-control" placeholder="Masukkan kode OTP" required>
                        </div>
                        @if ($errors->has('otp'))
                            <div class="alert alert-danger">{{ $errors->first('otp') }}</div>
                        @endif
                        <button type="submit" class="btn btn-primary">Verifikasi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
