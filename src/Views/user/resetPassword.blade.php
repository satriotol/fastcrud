@extends('layouts/layoutMaster')

@section('title', 'Ganti Password - Keamanan Akun')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <style>
        .security-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            border-radius: 15px;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .security-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .security-content {
            position: relative;
            z-index: 2;
        }

        .requirements-card {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            border-radius: 15px;
            color: white;
            margin-bottom: 2rem;
        }

        .main-card {
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .info-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 4px solid #2196f3;
            padding: 1.5rem;
            border-radius: 0 10px 10px 0;
            margin-bottom: 2rem;
        }

        .warning-box {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left: 4px solid #ffc107;
            padding: 1.5rem;
            border-radius: 0 10px 10px 0;
            margin-bottom: 2rem;
        }

        .success-box {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 4px solid #28a745;
            padding: 1.5rem;
            border-radius: 0 10px 10px 0;
            margin-bottom: 2rem;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .requirement-item:last-child {
            border-bottom: none;
        }

        .requirement-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }

        .password-strength {
            margin-top: 0.5rem;
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            text-align: center;
            display: none;
        }

        .strength-weak {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .strength-medium {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .strength-strong {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        @media (max-width: 768px) {
            .security-header {
                padding: 1.5rem 0;
                margin-bottom: 1rem;
            }

            .main-card {
                margin: 0.5rem;
            }
        }
    </style>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('multicol-password');
            const confirmInput = document.getElementById('multicol-confirm-password');
            const strengthIndicator = document.getElementById('password-strength');

            function checkPasswordStrength(password) {
                let score = 0;
                let feedback = [];

                if (password.length >= 8) score++;
                else feedback.push('Minimal 8 karakter');

                if (/[A-Z]/.test(password)) score++;
                else feedback.push('Huruf besar');

                if (/[a-z]/.test(password)) score++;
                else feedback.push('Huruf kecil');

                if (/[0-9]/.test(password)) score++;
                else feedback.push('Angka');

                if (/[@$!%*#?&_]/.test(password)) score++;
                else feedback.push('Simbol khusus');

                return {
                    score,
                    feedback
                };
            }

            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const result = checkPasswordStrength(password);

                if (password.length > 0) {
                    strengthIndicator.style.display = 'block';

                    if (result.score <= 2) {
                        strengthIndicator.className = 'password-strength strength-weak';
                        strengthIndicator.textContent = '⚠️ Password Lemah - Kurang: ' + result.feedback
                            .join(', ');
                    } else if (result.score <= 4) {
                        strengthIndicator.className = 'password-strength strength-medium';
                        strengthIndicator.textContent = '🔶 Password Sedang - Kurang: ' + result.feedback
                            .join(', ');
                    } else {
                        strengthIndicator.className = 'password-strength strength-strong';
                        strengthIndicator.textContent = '✅ Password Kuat - Memenuhi semua kriteria';
                    }
                } else {
                    strengthIndicator.style.display = 'none';
                }
            });

            // Konfirmasi sebelum submit
            document.querySelector('form').addEventListener('submit', function(e) {
                if (passwordInput.value !== confirmInput.value) {
                    e.preventDefault();
                    alert('Password dan konfirmasi password tidak sama!');
                    return false;
                }

                const confirmSubmit = confirm(
                    '⚠️ Setelah mengganti password, password lama tidak akan bisa digunakan lagi. Pastikan Anda mencatat password baru ini. Lanjutkan?'
                );
                if (!confirmSubmit) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            function togglePassword(inputId, toggleId) {
                const input = document.getElementById(inputId);
                const toggle = document.getElementById(toggleId);
                toggle.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('ti-eye-off');
                        icon.classList.add('ti-eye');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('ti-eye');
                        icon.classList.add('ti-eye-off');
                    }
                });
            }

            togglePassword('multicol-password', 'multicol-password-toggle');
            togglePassword('multicol-confirm-password', 'multicol-confirm-toggle');
        });
    </script>

@endsection

@section('content')
    <!-- Security Header -->
    <div class="security-header">
        <div class="security-content text-center">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-8 mx-auto">
                        <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                            <div
                                style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="ti ti-shield-lock"></i>
                            </div>
                            <div class="text-start">
                                <h2 class="mb-1 fw-bold text-white">Ganti Password</h2>
                                <p class="mb-0 opacity-75">Tingkatkan keamanan akun Anda</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Important Warning -->

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="warning-box">
                <div class="d-flex align-items-center gap-2">
                    <i class="ti ti-alert-triangle text-warning" style="font-size: 1.5rem;"></i>
                    <div>
                        <h6 class="mb-2 fw-bold text-warning">⚠️ PENTING - Baca Sebelum Melanjutkan!</h6>
                        <ul class="mb-0">
                            <li><strong>Password lama akan otomatis tidak aktif</strong> setelah berhasil diganti</li>
                            <li><strong>Catat password baru Anda</strong> di tempat yang aman dan mudah diingat</li>
                            <li><strong>Hubungi admin</strong> jika lupa password baru yang sudah dibuat</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="requirements-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="ti ti-checklist" style="font-size: 1.5rem;"></i>
                        <h5 class="mb-0 fw-bold text-white">Syarat Password Baru</h5>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="requirement-item">
                                <div class="requirement-icon">
                                    <i class="ti ti-check"></i>
                                </div>
                                <span>Minimal 8 karakter</span>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">
                                    <i class="ti ti-check"></i>
                                </div>
                                <span>Huruf besar (A-Z)</span>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">
                                    <i class="ti ti-check"></i>
                                </div>
                                <span>Huruf kecil (a-z)</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="requirement-item">
                                <div class="requirement-icon">
                                    <i class="ti ti-check"></i>
                                </div>
                                <span>Minimal satu angka (0-9)</span>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">
                                    <i class="ti ti-check"></i>
                                </div>
                                <span>Simbol khusus (@, $, !, %, *, #, ?, &, _)</span>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">
                                    <i class="ti ti-check"></i>
                                </div>
                                <span>Berbeda dari password sebelumnya</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card main-card">
                <div class="card-header bg-light d-flex align-items-center gap-2">
                    <i class="ti ti-key text-primary"></i>
                    <h5 class="mb-0 fw-bold">Form Ganti Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('password.change') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($user)
                            @method('PUT')
                        @endisset

                        @include('partials.errors')

                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="multicol-password">
                                <i class="ti ti-lock me-1"></i>Password Baru
                            </label>
                            <div class="input-group">
                                {{ html()->password('password')->placeholder('Masukkan Password Baru')->id('multicol-password')->class('form-control')->required(isset($user) ? false : true) }}
                                <span class="input-group-text cursor-pointer" id="multicol-password-toggle">
                                    <i class="ti ti-eye-off"></i>
                                </span>
                            </div>
                            <div id="password-strength" class="password-strength"></div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="multicol-confirm-password">
                                <i class="ti ti-lock-check me-1"></i>Konfirmasi Password Baru
                            </label>
                            <div class="input-group">
                                {{ html()->password('password_confirmation')->placeholder('Ulangi Password Baru')->id('multicol-confirm-password')->class('form-control')->required(isset($user) ? false : true) }}
                                <span class="input-group-text cursor-pointer" id="multicol-confirm-toggle">
                                    <i class="ti ti-eye-off"></i>
                                </span>
                            </div>
                            @error('password_confirmation')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="success-box">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ti ti-info-circle text-success"></i>
                                <div>
                                    <strong>Setelah berhasil mengganti password:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Login kembali menggunakan password baru</li>
                                        <li>Password lama sudah tidak dapat digunakan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i>Ganti Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
