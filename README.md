# Administrasi Pemancingan

Aplikasi **Administrasi Pemancingan** berbasis Laravel untuk membantu operasional tempat pemancingan: mengelola kolam/meja, mencatat transaksi pelanggan, menambahkan pesanan makanan/minuman, hingga membuat laporan pendapatan.

## Fitur Utama

- **Autentikasi pengguna** (login, register, verifikasi email, reset password).
- **Dashboard operasional** untuk melihat meja dan transaksi berjalan.
- **Manajemen meja/kolam** (CRUD meja + status tersedia/digunakan).
- **Transaksi pemancingan**:
  - Mulai sesi berdasarkan durasi.
  - Selesaikan sesi dengan perhitungan ikan kecil & ikan babon.
  - Batalkan atau hapus transaksi.
  - Histori transaksi.
- **Pesanan makanan/minuman** terhubung ke transaksi aktif.
- **Manajemen produk** (makanan/minuman) termasuk upload gambar.
- **Pengaturan akun** (profil, password, tema, foto profil).
- **Laporan transaksi** dengan filter tanggal dan **cetak PDF**.

## Teknologi

- **Backend:** PHP 8.2+, Laravel 12
- **Frontend assets:** Vite, Tailwind CSS, Alpine.js
- **Database:** MySQL/PostgreSQL/SQLite (sesuai konfigurasi Laravel)
- **Auth/API token:** Laravel Sanctum

## Struktur Modul

- `app/Http/Controllers/MejaController.php` → modul meja/kolam.
- `app/Http/Controllers/TransaksiController.php` → alur transaksi pemancingan, histori, laporan, cetak PDF.
- `app/Http/Controllers/PesananMakananController.php` → pesanan produk pada transaksi.
- `app/Http/Controllers/ProdukController.php` → CRUD produk makanan/minuman.
- `app/Http/Controllers/SettingsController.php` → profil, password, tema, foto profil.
- `routes/web.php` → routing utama aplikasi.

## Persiapan Environment

Pastikan sudah terpasang:

- PHP **8.2+**
- Composer
- Node.js + npm
- Database server (opsional jika tidak memakai SQLite)

## Instalasi

### Opsi cepat (disarankan)

Jalankan script setup bawaan `composer.json`:

```bash
composer run setup
```

Perintah ini akan:

1. Install dependency PHP.
2. Membuat `.env` dari `.env.example` (jika belum ada).
3. Generate app key.
4. Menjalankan migrasi database.
5. Install dependency frontend.
6. Build aset frontend.

### Opsi manual

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

## Menjalankan Aplikasi

### Mode development penuh (server + queue + log + vite)

```bash
composer run dev
```

### Atau jalankan terpisah

```bash
php artisan serve
npm run dev
```

Aplikasi akan tersedia di:

- `http://127.0.0.1:8000`

## Akun & Data Awal

Seeder tersedia di folder `database/seeders`. Jika ingin mengisi data awal:

```bash
php artisan db:seed
```

Atau reset sekaligus seed:

```bash
php artisan migrate:fresh --seed
```

## Pengujian

Menjalankan test suite:

```bash
composer test
```

atau

```bash
php artisan test
```

## Catatan Penyimpanan File

Aplikasi menggunakan disk `public` untuk upload (contoh: gambar produk, foto profil). Pastikan symbolic link storage sudah dibuat:

```bash
php artisan storage:link
```

## Ringkasan Route Penting

- `/dashboard` → halaman utama operasional.
- `/meja` → manajemen meja.
- `/transaksi/*` → alur transaksi, histori, laporan, cetak.
- `/produk` → manajemen produk.
- `/pesanan/create/{transaksi_id}` → input pesanan makanan/minuman.
- `/settings` → pengaturan profil, password, tema, foto.

## Lisensi

Project ini menggunakan lisensi [MIT](LICENSE).
