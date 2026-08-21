<?php

use App\Models\Galeri;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    $this->superAdmin = User::where('email', 'superadmin@example.com')->firstOrFail();
});

it('mengunggah gambar galeri dan menyajikannya lewat tautan publik', function () {
    Storage::fake(config('filesystems.default'));

    $this->actingAs($this->superAdmin)->post(route('galeri.store'), [
        'judul' => 'Kegiatan Uji Coba',
        'kategori' => 'foto',
        'tanggal' => '2026-08-01',
        'aktif' => 1,
        'gambar' => UploadedFile::fake()->image('kegiatan.jpg', 640, 400),
    ])->assertOk()->assertJson(['status' => true]);

    $galeri = Galeri::where('judul', 'Kegiatan Uji Coba')->firstOrFail();
    $berkas = $galeri->berkas('gambar');

    expect($berkas)->not->toBeNull()
        ->and(Storage::disk(config('filesystems.default'))->exists($berkas->target))->toBeTrue();

    // Halaman publik memakai tautan file-stream tanpa autentikasi.
    $this->get($berkas->link_public_stream)
        ->assertOk()
        ->assertHeader('x-content-type-options', 'nosniff');

    $this->get(route('web.galeri.show', $galeri->slug))->assertOk();
});

it('mengunggah logo lewat pengaturan website dan menghapusnya kembali', function () {
    Storage::fake(config('filesystems.default'));

    $this->actingAs($this->superAdmin)->post('admin/pengaturan', [
        'nilai' => ['nama_sekolah' => 'SMPN 5 Mandau'],
        'berkas' => ['logo' => UploadedFile::fake()->image('logo.png', 200, 200)],
    ])->assertRedirect(route('pengaturan.index'));

    expect(pengaturanBerkas('logo'))->not->toBeNull();

    $this->get('/')->assertOk();

    $this->actingAs($this->superAdmin)
        ->delete(route('pengaturan.berkas.hapus', 'logo'))
        ->assertRedirect(route('pengaturan.index'));

    Pengaturan::bersihkanCache();
    expect(pengaturanBerkas('logo'))->toBeNull();
});

it('menolak berkas yang bukan gambar pada modul galeri', function () {
    Storage::fake(config('filesystems.default'));

    $this->actingAs($this->superAdmin)->post(route('galeri.store'), [
        'judul' => 'Berkas Salah',
        'kategori' => 'foto',
        'gambar' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
    ])->assertSessionHasErrors('gambar');

    expect(Galeri::where('judul', 'Berkas Salah')->exists())->toBeFalse();
});
