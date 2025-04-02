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
                    @if (!$user->google2fa_verified)
                        {!! $google2fa_url !!}
                    @else
                        <div class="alert alert-info">
                            2FA sudah aktif dan diverifikasi. Jika Anda kehilangan akses, silakan hubungi administrator
                            untuk reset 2FA.
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <p>Silakan scan QR Code dengan aplikasi Google Authenticator atau Authy, lalu masukkan kode OTP yang
                        muncul.</p>
                    <form action="{{ route('2fa.verify') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="otp" class="form-label">Kode OTP</label>
                            <input type="text" id="otp" name="otp" class="form-control"
                                placeholder="Masukkan kode OTP" required>
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
    <div class="card mt-4">
        <div class="card-header">
            <h4 class="card-title">Panduan Mengaktifkan Two-Factor Authentication (2FA)</h4>
        </div>
        <div class="card-body">
            <h5>1. Instal Aplikasi Authenticator</h5>
            <p>Untuk menggunakan 2FA, Anda memerlukan aplikasi autentikasi pihak ketiga seperti:</p>
            <ul>
                <li><strong>Google Authenticator</strong> (Android/iOS)</li>
                <li><strong>FreeOTP</strong> (Android/iOS)</li>
                <li><strong>Authy</strong> (Android/iOS)</li>
            </ul>
            <p>Unduh dan instal salah satu aplikasi tersebut melalui Google Play Store atau Apple App Store.</p>
            <h5>2. Pindai Kode QR</h5>
            <p>1. Buka aplikasi autentikator di smartphone Anda.<br>
                2. Pilih <strong>Tambah Akun</strong> atau ikon <strong>+</strong>.<br>
                3. Pilih opsi <strong>Pindai Kode QR</strong> dan arahkan kamera ke kode QR yang ditampilkan di layar
                aplikasi.<br>
                4. Aplikasi akan menambahkan akun dan menampilkan kode OTP (One-Time Password) yang berubah setiap
                beberapa detik.</p>
            <h5>3. Verifikasi OTP</h5>
            <p>1. Masukkan kode OTP yang muncul di aplikasi autentikasi ke dalam kolom <strong>Kode OTP</strong> pada
                formulir verifikasi.<br>
                2. Klik tombol <strong>Verifikasi</strong>.<br>
                3. Jika kode yang dimasukkan benar, maka 2FA akan berhasil diaktifkan.<br>
                4. Anda akan melihat notifikasi bahwa 2FA telah diaktifkan.</p>
            <h5>4. Jika Kehilangan Akses ke 2FA</h5>
            <p>Jika Anda kehilangan akses ke aplikasi autentikator:</p>
            <ul>
                <li>Hubungi administrator untuk melakukan reset 2FA.</li>
                <li>Gunakan metode pemulihan jika tersedia.</li>
            </ul>
        </div>
    </div>
@endsection
