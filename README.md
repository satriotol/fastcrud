# Laravel Fastcrud By Satrio Tamvan

FastCrud tidak hanya menawarkan fitur dasar pembuatan CRUD, tetapi juga mencakup manajemen hak akses dan audit, menjadikannya alat yang lebih lengkap untuk pengembangan aplikasi Laravel. Berikut adalah tambahan fitur terkait hak akses dan audit:

- **Hak Akses Pengguna:** FastCrud terintegrasi dengan sistem manajemen hak akses Laravel, seperti Spatie's Laravel Permissions, sehingga Anda dapat dengan mudah mengelola izin dan peran pengguna dalam operasi CRUD. Anda dapat menentukan hak akses untuk berbagai operasi (seperti baca, tulis, edit, hapus) dan mengelola siapa yang dapat mengakses atau memodifikasi data.

- **Audit:** FastCrud juga mendukung fitur audit untuk melacak perubahan yang terjadi pada data Anda. Fitur audit ini membantu Anda mencatat aktivitas CRUD, termasuk siapa yang membuat, memperbarui, atau menghapus data, serta kapan perubahan tersebut terjadi. Ini penting untuk keperluan pelaporan, keamanan, dan kepatuhan.

Dengan fitur-fitur ini, FastCrud memberikan solusi yang komprehensif untuk pengembangan aplikasi Laravel, memungkinkan pengembang untuk menangani hak akses dan audit secara efisien tanpa harus menulis kode secara manual.

# Instalasi FastCrud

Ikuti langkah-langkah berikut untuk menginstal dan mengonfigurasi FastCrud dalam proyek Laravel Anda:

## 1. Buat Proyek Laravel

Jika Anda belum memiliki proyek Laravel, buat proyek baru dengan perintah berikut:

```bash
laravel new {project_name}
```

Gantilah `{project_name}` dengan nama proyek Anda.

## 2. Instal FastCrud

Tambahkan FastCrud ke proyek Laravel Anda menggunakan Composer:

```bash
composer require satriotol/fastcrud
```

## 3. Jalankan Perintah Vendor Publish

Publikasikan file konfigurasi dan stubs FastCrud dengan perintah berikut:

```bash
php artisan vendor:publish --force
```

Ketika Anda menjalankan perintah ini, pilih provider berikut:

```
Provider: Satriotol\Fastcrud\FastCrudServiceProvider
```

Ini akan memastikan bahwa semua file yang diperlukan dari FastCrud dipublikasikan ke proyek Anda.

## 4. Buat Pengguna Superadmin

Untuk membuat pengguna superadmin, jalankan perintah berikut di terminal:

```bash
php artisan fastcrud:create_user
```

Ikuti langkah-langkah berikut:

- Masukkan nama pengguna.
- Masukkan email pengguna.
- Masukkan password (gunakan password yang aman dan standar).

Dengan langkah-langkah ini, FastCrud akan terinstal dan siap digunakan dalam proyek Laravel Anda. Selamat mengembangkan aplikasi!

# 🔧 Pengaturan Tambahan (.env)

FastCrud menyediakan beberapa pengaturan tambahan melalui file `.env` untuk mendukung fitur Two-Factor Authentication (2FA) dan reset password otomatis.

---

## 🔐 Two-Factor Authentication (2FA)

FastCrud mendukung autentikasi dua faktor untuk meningkatkan keamanan pengguna. Anda dapat mengatur perilaku 2FA melalui pengaturan berikut:

| Variabel                 | Deskripsi                                                                                                                                          |
| ------------------------ | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| `GOOGLE2FA_EXCEPT_ROLES` | Role yang tidak dikenakan 2FA. Gunakan tanda koma (`,`) untuk memisahkan beberapa role. <br> **Contoh:** `GOOGLE2FA_EXCEPT_ROLES=SUPERADMIN,ADMIN` |
| `APP_DEBUG`              | Jika disetel ke `true`, pengecekan 2FA dinonaktifkan. Berguna untuk lingkungan development. <br> **Contoh:** `APP_DEBUG=true`                      |

💡 **Catatan:** Pastikan pengguna Anda telah diatur dengan role menggunakan **Spatie Laravel Permissions** agar pengecualian dapat berjalan dengan benar.

---

## 🔄 Reset Password Otomatis

FastCrud mendukung sistem reset password berkala berdasarkan batas waktu yang Anda tentukan. Ini sangat berguna untuk meningkatkan keamanan akun pengguna.

| Variabel                 | Deskripsi                                                                                                                                                       |
| ------------------------ | --------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `PASSWORD_EXPIRY_MONTHS` | Menentukan jumlah bulan masa berlaku password. Setelah periode ini, pengguna akan diminta untuk mengganti password. <br> **Contoh:** `PASSWORD_EXPIRY_MONTHS=6` |

## Authors

- [@satriotol](https://github.com/satriotol)
