# Master 2024

Template Laravel 13 untuk dipakai berulang pada project-project baru yang butuh fondasi admin panel, autentikasi, role-permission, dan generator CRUD.

> **Branch ini** berisi implementasi **Website Sekolah SMPN 5 Mandau** di atas template ini:
> halaman publik (beranda, profil, program sekolah, GTK, kesiswaan, sarana prasarana, galeri, kontak),
> modul konten di panel admin, pengumuman kelulusan (login NISN + tanggal lahir, import Excel),
> dan SPMB online (pendaftaran + export Excel).
> Dokumentasi lengkapnya: [`docs/website-sekolah.md`](docs/website-sekolah.md).

## Versi Dasar

- Laravel: `13.x`
- PHP minimum: `8.3`
- Status config project: sedang disinkronkan ke struktur Laravel 13

## Tujuan Project

Project ini bukan hanya aplikasi jadi, tapi `master template` yang harus:

- cepat di-clone dan langsung bisa dipakai
- punya fondasi auth, user, role, dan permission yang konsisten
- mempermudah pembuatan module baru lewat generator CRUD
- aman dijadikan titik awal untuk project turunan

## Prioritas Master Reusable

Kalau project ini ingin nyaman dipakai berulang, fokus utamanya ada di 5 area ini:

1. `Bootstrap aman`
Project harus tetap bisa `install`, `migrate`, `seed`, `test`, dan `route:list` meskipun data menu awal belum lengkap.

2. `Auth dan permission konsisten`
Backend dan API harus memakai pola user, role, permission, dan proteksi `Super Admin` yang sama.

3. `Generator CRUD benar-benar siap pakai`
Hasil generate idealnya langsung punya route, controller, request validation, permission, view, dan struktur yang konsisten.

4. `Testing minimal tersedia`
Master template sebaiknya punya test untuk login, user, role, permission, dan generator agar aman dipakai ulang.

5. `Dokumentasi clone-and-go`
README harus menjelaskan cara setup, credential awal, bagian yang wajib diganti, dan alur adaptasi ke project baru.

## Requirements

- PHP >= 8.3
- Composer
- MySQL / MariaDB
- Node.js dan npm
- Git

## Quick Start

### 1. Clone repository

```bash
git clone https://github.com/mikhsanw/master2024.git
cd master2024
```

### 2. Install dependency

```bash
composer install
npm install
```

### 3. Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

Lalu sesuaikan minimal bagian berikut di `.env`:

- `APP_NAME`
- `APP_URL`
- `APP_TIMEZONE`
- `APP_LOCALE`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `SESSION_DRIVER`
- `QUEUE_CONNECTION`
- `CACHE_STORE`

### 4. Jalankan migration dan seeder

```bash
php artisan migrate --seed
```

Seeder default akan membuat:

- role `Admin`
- role `Super Admin`
- user admin awal
- menu dasar backend

### 5. Jalankan aplikasi

```bash
php artisan serve
npm run dev
```

Akses aplikasi di:

- `http://127.0.0.1:8000`
- atau virtual host lokal seperti `http://master2024.test`

## Default Login

- Email: `superadmin@example.com`
- Password: `unique`

## Fitur Utama

- autentikasi berbasis Laravel
- role dan permission berbasis Spatie
- admin panel berbasis Blade
- menu management
- activity log
- CRUD generator
- API auth dan master data dasar

## CRUD Generator

Project ini menyediakan command untuk membuat dan menghapus CRUD secara otomatis.

### Create CRUD

Format:

```bash
php artisan make:crud ModelName "field:type,field:type"
```

Contoh:

```bash
php artisan make:crud Post "nama:string,alamat:text,nip:integer,berita_id:relasi:Berita"
```

Generator saat ini ditujukan untuk mempercepat pembuatan:

- model
- migration
- controller
- views blade
- route backend

### Remove CRUD

```bash
php artisan rm:crud Post
```

Force delete tanpa konfirmasi:

```bash
php artisan rm:crud Post --force
```

## Checklist Saat Dipakai untuk Project Baru

Setelah clone project ini untuk project turunan, cek dan ubah bagian berikut:

- nama aplikasi di `.env` dan `config/master.php`
- URL aplikasi backend dan frontend
- logo, favicon, dan asset branding
- default menu pada seeder
- default role dan permission
- konfigurasi mail, queue, cache, dan filesystem
- konfigurasi backup Google Drive jika dipakai
- data dummy atau contoh yang tidak relevan

## Checklist Kelayakan Master

Gunakan checklist ini setiap kali project ini mau dijadikan baseline baru:

- app bisa jalan setelah `migrate --seed`
- login admin awal berfungsi
- role dan permission dapat dipakai tanpa edit manual
- generator CRUD menghasilkan module yang konsisten
- API auth dan API master data berjalan
- test inti lulus
- dokumentasi sesuai kondisi project terbaru

## Saran Pengembangan Berikutnya

Supaya nilai reusable project ini makin tinggi, pengembangan berikut paling penting:

1. lepaskan dependency database dari bootstrap controller
2. samakan kontrak auth user antara backend dan API
3. tambahkan authorization yang konsisten di API user dan role
4. tambahkan test untuk auth, user, role, layout, dan generator
5. dokumentasikan alur membuat project turunan dari master ini

## License

This project is open-sourced software licensed under the MIT license.
