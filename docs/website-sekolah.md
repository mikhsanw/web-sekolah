# Website Sekolah — SMPN 5 Mandau

Dokumentasi modul website sekolah yang dibangun di atas template **Master 2024**
(Laravel 13, PHP 8.3+). Berisi struktur halaman publik, modul panel admin,
alur kelulusan & SPMB, serta catatan operasional.

---

## 1. Ringkasan

| Bagian | Keterangan |
|--------|------------|
| Halaman publik | `routes/frontend.php` → `App\Http\Controllers\Frontend\*` → `resources/views/frontend/*` |
| Panel admin | `routes/backend.php` → `App\Http\Controllers\Backend\*` → `resources/views/backend/*` |
| Aset publik | `public/sekolah/{css,js,img}` (folder diatur di `config('master.app.web.template')`) |
| Konfigurasi sekolah | `config/sekolah.php` (default & enum) + tabel `pengaturans` (nilai aktif) |
| Navigasi publik | `config('sekolah.navigasi')`, dirakit `App\Http\Middleware\Frontend` |

Halaman publik **tidak** memakai framework CSS eksternal maupun CDN. Seluruh gaya
ada pada satu berkas `public/sekolah/css/style.css` dan satu berkas
`public/sekolah/js/main.js`.

---

## 2. Struktur halaman publik

```
/                                   Beranda (berita terbaru + kalender + galeri)
/berita, /berita/{slug}             Berita sekolah
/profil/{slug}                      Halaman statis (sejarah, visi-misi, sambutan, logo, kurikulum, informasi siswa)
/struktur-organisasi                Bagan struktur + daftar pejabat
/hymne                              Pemutar & unduh hymne (MP3)
/agenda                             Agenda kegiatan + kalender
/ekstrakurikuler                    Daftar ekstrakurikuler
/gtk/guru, /gtk/tenaga-kependidikan Informasi guru & tendik
/prestasi/{siswa|guru}              Prestasi siswa / guru
/sarana-prasarana/peta-sekolah      Denah + Google Maps
/sarana-prasarana/fasilitas         Daftar fasilitas
/galeri, /galeri/{slug}             Galeri foto & video
/kontak                             Alamat, kontak, media sosial
/pencarian?q=                       Pencarian berita, halaman, agenda, prestasi
/kelulusan                          Daftar angkatan
/kelulusan/{angkatan}               Form masuk NISN + tanggal lahir
/kelulusan/{angkatan}/hasil         Hasil kelulusan (dapat dicetak)
/spmb/informasi                     Informasi & alur SPMB
/spmb/pendaftaran                   Masuk NISN + tanggal lahir
/spmb/formulir                      Formulir calon peserta didik
/spmb/bukti-pendaftaran             Bukti pendaftaran (dapat dicetak)
```

Susunan menu navigasi ada di `config/sekolah.php` pada kunci `navigasi`.
Submenu **Kelulusan** diisi otomatis dari periode kelulusan yang aktif.

---

## 3. Modul panel admin

Semua modul mengikuti pola master template: datatable server-side + form modal AJAX,
permission `"{kode} {list|create|edit|delete}"`, dan menu pada tabel `menus`.

| Menu | Kode | Model | Catatan |
|------|------|-------|---------|
| Konten Website → Halaman Statis | `halaman` | `Halaman` | Sejarah, visi misi, sambutan, kurikulum, dll |
| Konten Website → Agenda | `agenda` | `Agenda` | Sumber data kalender di halaman depan |
| Konten Website → Galeri | `galeri` | `Galeri` | Foto & video (YouTube) |
| Konten Website → Prestasi | `prestasi` | `Prestasi` | Kategori siswa / guru |
| Profil Sekolah → Guru & Tendik | `pegawai` | `Pegawai` | Jenis `guru` / `tendik` |
| Profil Sekolah → Ekstrakurikuler | `ekstrakurikuler` | `Ekstrakurikuler` | |
| Profil Sekolah → Sarana Prasarana | `fasilitas` | `Fasilitas` | |
| Kelulusan → Periode Kelulusan | `periode-kelulusan` | `KelulusanPeriode` | Angkatan + jadwal pengumuman |
| Kelulusan → Data Kelulusan | `kelulusan` | `Kelulusan` | Import & export Excel |
| SPMB → Periode SPMB | `periode-spmb` | `SpmbPeriode` | Gelombang, kuota, syarat |
| SPMB → Pendaftar SPMB | `spmb` | `SpmbPendaftar` | Verifikasi + export Excel |
| Pengaturan Website | `pengaturan` | `Pengaturan` | Identitas, kontak, logo, hymne, warna |

### Pengaturan Website

`Admin → Pengaturan Website` mengelola tabel `pengaturans` dengan empat kelompok:

- **Identitas Sekolah** — nama, NPSN, akreditasi, kepala sekolah, alamat, telepon, email, moto
- **Kontak & Media Sosial** — Facebook, Instagram, YouTube, WhatsApp, URL embed Google Maps
- **Logo, Hymne & Gambar** — logo, favicon, hymne (MP3), bagan struktur organisasi, denah sekolah
- **Tampilan Website** — warna utama, warna aksen, teks footer

Nilai dibaca lewat helper `pengaturan('kunci')` dan `pengaturanBerkas('kunci')`
(keduanya di-cache; cache dibersihkan otomatis setiap penyimpanan).
Default-nya berasal dari `config/sekolah.php`, sehingga website tetap tampil
sebelum admin mengisi apa pun.

### Role & Hak Akses

Ada dua role bawaan, dengan pemisahan tegas antara **mengonfigurasi aplikasi**
dan **mengoperasikan kontennya**:

| Role | Lingkup | Tidak bisa |
|------|---------|------------|
| **Super Admin** | Semuanya — bypass seluruh pengecekan permission lewat `Gate::before` (`AppServiceProvider`) dan `CheckRoutePermission`. Satu-satunya role yang boleh mengubah Role & Permission, Menu navigasi, Settings (backup), dan Pengaturan Website, serta memberi role Super Admin ke pengguna lain. | — |
| **Admin** | Operasional harian: seluruh modul konten website sekolah (Halaman, Agenda, Galeri, Prestasi, Pegawai, Ekstrakurikuler, Fasilitas, Kelulusan, SPMB, Berita) dan manajemen akun pengguna biasa. | Menu **Role**, **Menu**, **Settings**, dan **Pengaturan Website** — otomatis tidak tampil di sidebar dan rutenya menolak akses (403) meski URL diketik langsung. Juga tidak bisa memberi role **Super Admin** ke akun mana pun (divalidasi di server, bukan hanya disembunyikan di form). |

Pemisahan ini diatur di `database/seeders/DatabaseSeeder.php` lewat dua daftar
permission terpisah — `$permissionsSistem` (khusus Super Admin, tidak pernah
disinkronkan ke role Admin) dan `$permissionsOperasional` (disinkronkan ke
Admin). Modul baru hasil `php artisan make:crud` otomatis masuk ke daftar
operasional (lewat marker `// gencrud-permissions`); pindahkan manual ke
`$permissionsSistem` bila modul barunya bersifat konfigurasi aplikasi, bukan
konten.

Guna menambah role baru yang lebih sempit (mis. "Panitia SPMB" yang hanya
boleh menyentuh modul SPMB), buat lewat `Admin → Role` (menu ini sendiri
hanya terlihat oleh Super Admin) dan centang permission modul yang relevan
saja — tidak perlu mengubah kode.

---

## 4. Alur Kelulusan

1. Admin membuat **Periode Kelulusan** (mis. `2025/2026`) berisi jadwal pengumuman,
   rentang tanggal pengambilan administrasi, tempat, dan keterangan.
2. Admin mengunggah data siswa lewat **Data Kelulusan → Import Excel**.
   Unduh template terlebih dahulu pada tombol *Unduh Template*.

   Kolom berkas Excel:

   | Kolom | Wajib | Keterangan |
   |-------|-------|------------|
   | `NISN` | ya | Kunci pencocokan data |
   | `NIS` | tidak | |
   | `Nama` | ya | |
   | `Tanggal Lahir` | ya | `YYYY-MM-DD` atau format tanggal Excel |
   | `Kelas` | tidak | |
   | `Status` | tidak | `lulus` (default) atau `tidak_lulus` |
   | `Keterangan` | tidak | Ditampilkan pada halaman hasil |
   | `Tanggal Pengambilan` | tidak | Menimpa jadwal periode untuk siswa tersebut |

   Centang *Perbarui data yang NISN-nya sudah ada* bila ingin menimpa data lama.
   Baris tanpa NISN/nama/tanggal lahir dilewati dan dilaporkan di layar.
3. Saat siap, admin mengaktifkan **Pengumuman Dibuka** pada periode tersebut.
   Hasil baru dapat diakses bila flag aktif **dan** `tanggal_pengumuman` sudah lewat.
4. Siswa membuka `/kelulusan`, memilih angkatan, lalu masuk dengan **NISN + tanggal lahir**.
   Halaman hasil menampilkan status kelulusan dan informasi tanggal pengambilan
   administrasi, serta dapat dicetak.

Percobaan masuk dibatasi `throttle:10,1` untuk mencegah penebakan data siswa.

---

## 5. Alur SPMB

1. Admin membuat **Periode SPMB** (tahun ajaran, tanggal buka/tutup, kuota,
   informasi, persyaratan) lalu mengaktifkan **Pendaftaran Dibuka**.
2. Calon peserta didik membuka `/spmb/pendaftaran` dan masuk dengan
   **NISN + tanggal lahir**:
   - NISN belum terdaftar → diarahkan ke formulir pendaftaran.
   - NISN sudah terdaftar → tanggal lahir dipakai sebagai kunci akses menuju bukti pendaftaran.
3. Formulir mengumpulkan: asal sekolah, NISN, NIK, nama, jenis kelamin, agama,
   tempat & tanggal lahir, alamat, nomor HP, dan nama orang tua/wali.
   NISN dan tanggal lahir dikunci dari sesi sehingga tidak dapat ditukar lewat form.
4. Setelah tersimpan, sistem menerbitkan nomor pendaftaran `SPMB-{tahun}-{urut}` dan
   menampilkan bukti pendaftaran yang dapat dicetak.
5. Data hanya bisa diubah pendaftar selama status masih `Menunggu Verifikasi`.
6. Admin memverifikasi di **SPMB → Pendaftar SPMB** (ubah status menjadi
   Terverifikasi / Diterima / Ditolak) dan mengunduh seluruh data lewat
   **Export Excel** (mendukung filter periode & status).

---

## 6. Berkas & media

Unggahan memakai sistem `File` bawaan template (relasi morph + disk storage):

- Trait `App\Models\Concerns\PunyaFile` menyediakan `berkas($alias)`,
  `berkasUrl($alias, $default)`, dan `simpanBerkas($upload, $alias, $folder)`.
- Halaman publik memakai `link_public_stream` (`/file-stream/{code}?id=`),
  panel admin memakai `link_stream` (`/admin/file/stream/...`).
- Alias yang dipakai: `gambar` (halaman, ekskul, prestasi, fasilitas, galeri),
  `foto` (pegawai), serta kunci pengaturan (`logo`, `favicon`, `hymne`,
  `struktur_organisasi`, `peta_sekolah`).

---

## 7. Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Seeder `SekolahSeeder` bersifat idempotent dan mengisi:
pengaturan awal, 10 halaman profil, agenda contoh, ekstrakurikuler, data GTK contoh,
prestasi, fasilitas, galeri, dua berita pengantar, dua periode kelulusan
(2025/2026 & 2026/2027, keduanya belum dibuka), dan satu periode SPMB 2026/2027.

Login admin awal: `superadmin@example.com` / `unique`.

### Yang wajib diganti sebelum dipakai nyata

1. **Pengaturan Website** — alamat, NPSN, telepon, email, kepala sekolah, media sosial, logo, hymne.
2. **Halaman Statis** — sejarah, sambutan, visi misi, kurikulum, informasi siswa.
3. **Guru & Tendik** — data contoh diganti data asli.
4. **Periode Kelulusan / SPMB** — tahun ajaran dan jadwal sebenarnya.
5. **Kredensial admin** — ganti password `superadmin@example.com` dan hapus akun contoh.

---

## 8. Pengujian

```bash
php artisan test --filter=Sekolah
```

Berkas uji ada di `tests/Feature/Sekolah/`:

- `HalamanPublikTest` — seluruh halaman publik, pencarian, detail berita & galeri, kalender
- `KelulusanTest` — akses pengumuman, validasi NISN/tanggal lahir, periode belum dibuka, keluar sesi
- `SpmbTest` — pendaftaran baru, kunci NISN dari sesi, pendaftar lama, periode ditutup, validasi, penguncian setelah verifikasi
- `BackendSekolahTest` — akses seluruh modul admin, JSON datatable, siklus CRUD, import/export Excel, pengaturan
- `BerkasSekolahTest` — unggah gambar galeri, unggah & hapus logo, penolakan berkas non-gambar

---

## 9. Catatan teknis

- `App\Http\Middleware\Frontend` membagikan `$identitas`, `$menuWeb`, dan `$templateWeb`
  ke seluruh view publik, serta ditulis tahan-gagal agar halaman depan tetap tampil
  saat database belum di-migrate.
- `App\Services\KalenderSekolah` menyiapkan data widget kalender (bulan, hari beragenda, agenda terdekat).
- `App\Services\BerkasExcel` membungkus PhpSpreadsheet untuk baca/tulis `.xlsx`
  termasuk konversi tanggal serial Excel.
- Controller frontend mewarisi `App\Http\Controllers\Frontend\FrontendController`
  (bukan controller dasar backend) karena controller dasar bergantung pada tabel menu admin.
