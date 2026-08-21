<?php

use App\Http\Controllers\Frontend\BerandaController;
use App\Http\Controllers\Frontend\BeritaController;
use App\Http\Controllers\Frontend\GaleriController;
use App\Http\Controllers\Frontend\GtkController;
use App\Http\Controllers\Frontend\HalamanController;
use App\Http\Controllers\Frontend\KelulusanController;
use App\Http\Controllers\Frontend\KontakController;
use App\Http\Controllers\Frontend\PencarianController;
use App\Http\Controllers\Frontend\PrestasiController;
use App\Http\Controllers\Frontend\ProgramController;
use App\Http\Controllers\Frontend\SarprasController;
use App\Http\Controllers\Frontend\SpmbController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Halaman Publik (Website Sekolah)
|--------------------------------------------------------------------------
| Semua rute di sini memakai middleware `web` + `frontend`
| (lihat bootstrap/app.php).
*/

Route::get('/', [BerandaController::class, 'index'])->name('web.beranda');

// Berita
Route::get('/berita', [BeritaController::class, 'index'])->name('web.berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('web.berita.show');

// Profil & halaman statis
Route::get('/profil/{halaman}', [HalamanController::class, 'show'])->name('web.profil');
Route::get('/struktur-organisasi', [HalamanController::class, 'struktur'])->name('web.struktur');
Route::get('/hymne', [HalamanController::class, 'hymne'])->name('web.hymne');

// Program sekolah
Route::get('/agenda', [ProgramController::class, 'agenda'])->name('web.agenda');
Route::get('/ekstrakurikuler', [ProgramController::class, 'ekstrakurikuler'])->name('web.ekstrakurikuler');

// GTK
Route::get('/gtk/guru', [GtkController::class, 'guru'])->name('web.guru');
Route::get('/gtk/tenaga-kependidikan', [GtkController::class, 'tendik'])->name('web.tendik');

// Prestasi (siswa & guru)
Route::get('/prestasi/{kategori}', [PrestasiController::class, 'index'])
    ->whereIn('kategori', ['siswa', 'guru'])
    ->name('web.prestasi');

// Sarana prasarana
Route::get('/sarana-prasarana/peta-sekolah', [SarprasController::class, 'peta'])->name('web.peta');
Route::get('/sarana-prasarana/fasilitas', [SarprasController::class, 'fasilitas'])->name('web.fasilitas');

// Galeri
Route::get('/galeri', [GaleriController::class, 'index'])->name('web.galeri.index');
Route::get('/galeri/{slug}', [GaleriController::class, 'show'])->name('web.galeri.show');

// Kontak & pencarian
Route::get('/kontak', [KontakController::class, 'index'])->name('web.kontak');
Route::get('/pencarian', [PencarianController::class, 'index'])->name('web.pencarian');

/*
|--------------------------------------------------------------------------
| Pengumuman Kelulusan
|--------------------------------------------------------------------------
| Siswa masuk memakai NISN + tanggal lahir. Percobaan masuk dibatasi
| throttle untuk mencegah penebakan data siswa.
*/
Route::prefix('kelulusan')->as('web.kelulusan.')->group(function () {
    Route::get('/', [KelulusanController::class, 'index'])->name('index');
    Route::get('/{periode}', [KelulusanController::class, 'periode'])->name('periode');
    Route::post('/{periode}/cek', [KelulusanController::class, 'cek'])
        ->middleware('throttle:10,1')->name('cek');
    Route::get('/{periode}/hasil', [KelulusanController::class, 'hasil'])->name('hasil');
    Route::post('/{periode}/keluar', [KelulusanController::class, 'keluar'])->name('keluar');
});

/*
|--------------------------------------------------------------------------
| SPMB — Seleksi Penerimaan Murid Baru
|--------------------------------------------------------------------------
*/
Route::prefix('spmb')->as('web.spmb.')->group(function () {
    Route::get('/', [SpmbController::class, 'informasi'])->name('index');
    Route::get('/informasi', [SpmbController::class, 'informasi'])->name('informasi');
    Route::get('/pendaftaran', [SpmbController::class, 'pendaftaran'])->name('pendaftaran');
    Route::post('/pendaftaran', [SpmbController::class, 'masuk'])
        ->middleware('throttle:10,1')->name('masuk');
    Route::get('/formulir', [SpmbController::class, 'formulir'])->name('formulir');
    Route::post('/formulir', [SpmbController::class, 'simpan'])->name('simpan');
    Route::get('/bukti-pendaftaran', [SpmbController::class, 'bukti'])->name('bukti');
    Route::post('/keluar', [SpmbController::class, 'keluar'])->name('keluar');
});
