# Hedging Syariah

Aplikasi web sederhana untuk manajemen hedging syariah berbasis PHP dan MySQL. Fitur utama:

- Autentikasi dan otorisasi dengan peran **admin**, **auditor**, dan **user**.
- CRUD eksposur dengan unggah bukti underlying ke direktori `uploads/`.
- CRUD transaksi hedging dengan pilihan akad syariah (Waad FX, Murabahah, Tawarruq, Salam, Istishna).
- Validasi kepatuhan syariah otomatis (underlying wajib, tujuan tahawwut, biaya transparan, tanpa denda riba, notional ≤ eksposur + 5%).
- Review syariah oleh admin/auditor untuk approve/reject beserta catatan.
- Audit log aktivitas penting dan laporan HTML/CSV.
- Antarmuka Bootstrap 5.

## Prasyarat

- PHP 8.0 atau lebih baru dengan ekstensi `pdo_mysql`.
- MySQL 5.7+/MariaDB.
- Web server (Apache/Nginx) atau gunakan server bawaan PHP.

## Konfigurasi

1. Salin repositori ini ke server/webroot Anda.
2. Salin file `.env.example` (tidak tersedia) atau atur variabel lingkungan berikut untuk koneksi database:
   - `DB_HOST`
   - `DB_DATABASE`
   - `DB_USERNAME`
   - `DB_PASSWORD`
3. Atau edit `config/database.php` secara langsung sesuai kredensial MySQL Anda.

## Instalasi Database

Jalankan perintah berikut dari root proyek untuk membuat tabel dan data awal:

```bash
php bin/migrate.php
php bin/seed.php
```

Perintah pertama akan menjalankan seluruh migrasi di `database/migrations`. Perintah kedua menambahkan tiga akun default:

- `admin` / `password`
- `auditor` / `password`
- `user` / `password`

Silakan ubah password setelah login pertama.

## Menjalankan Aplikasi

Gunakan server bawaan PHP:

```bash
php -S localhost:8000 -t public
```

Buka `http://localhost:8000` di browser dan login menggunakan kredensial di atas.

## Struktur Direktori

- `public/` – Entry point aplikasi.
- `app/Controllers` – Controller HTTP.
- `app/Models` – Akses data dan logika bisnis.
- `app/Views` – Template Bootstrap.
- `app/Core` – Komponen inti (router, database, validator, uploader).
- `database/migrations` – Migrasi skema.
- `database/seeds` – Seeder data.
- `uploads/` – Lokasi penyimpanan bukti underlying (pastikan dapat ditulis server).

## Catatan Keamanan

- Pastikan direktori `uploads/` tidak dieksekusi sebagai skrip pada server produksi.
- Gunakan HTTPS dan ganti kredensial default segera.
- Validasi tambahan dapat ditambahkan sesuai kebutuhan internal perusahaan.

## Lisensi

Proyek ini dapat dikembangkan lebih lanjut sesuai kebutuhan organisasi Anda.
