<?php

use App\Models\Berita;
use App\Models\Galeri;
use Database\Seeders\SekolahSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(SekolahSeeder::class);
});

it('menampilkan beranda beserta widget kalender dan galeri', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Berita Terbaru')
        ->assertSee('Kalender')
        ->assertSee(config('sekolah.identitas.nama'));
});

it('membuka seluruh halaman publik utama', function (string $url) {
    $this->get($url)->assertOk();
})->with([
    '/berita',
    '/profil/sejarah-singkat',
    '/profil/visi-dan-misi',
    '/profil/kurikulum',
    '/struktur-organisasi',
    '/hymne',
    '/agenda',
    '/ekstrakurikuler',
    '/gtk/guru',
    '/gtk/tenaga-kependidikan',
    '/prestasi/siswa',
    '/prestasi/guru',
    '/sarana-prasarana/peta-sekolah',
    '/sarana-prasarana/fasilitas',
    '/galeri',
    '/kontak',
    '/kelulusan',
    '/spmb/informasi',
    '/spmb/pendaftaran',
]);

it('menolak halaman statis yang tidak ada', function () {
    $this->get('/profil/tidak-ada-halaman')->assertNotFound();
});

it('mencari konten lewat halaman pencarian', function () {
    $this->get('/pencarian?q=visi')
        ->assertOk()
        ->assertSee('Visi dan Misi');
});

it('mengabaikan kata kunci pencarian yang terlalu pendek', function () {
    $this->get('/pencarian?q=ab')
        ->assertOk()
        ->assertSee('minimal 3 huruf');
});

it('menampilkan detail berita dan menambah jumlah pembaca', function () {
    $berita = Berita::query()->firstOrFail();
    $viewAwal = (int) $berita->view;

    $this->get('/berita/'.$berita->slug)
        ->assertOk()
        ->assertSee($berita->judul);

    expect((int) $berita->fresh()->view)->toBe($viewAwal + 1);
});

it('menampilkan galeri beserta halaman detailnya', function () {
    $galeri = Galeri::query()->firstOrFail();

    $this->get('/galeri/'.$galeri->slug)
        ->assertOk()
        ->assertSee($galeri->judul);
});

it('menyaring agenda berdasarkan jenis', function () {
    $this->get('/agenda?jenis=libur')
        ->assertOk()
        ->assertSee('Libur Semester Ganjil');
});

it('menampilkan kalender pada bulan yang dipilih', function () {
    $this->get('/?bulan=8&tahun='.date('Y'))
        ->assertOk()
        ->assertSee('Agustus '.date('Y'));
});
