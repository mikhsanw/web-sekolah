<?php

use App\Models\Kelulusan;
use App\Models\KelulusanPeriode;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function buatPeriodeKelulusan(array $atribut = []): KelulusanPeriode
{
    return KelulusanPeriode::create(array_merge([
        'tahun_ajaran' => '2025/2026',
        'judul' => 'Pengumuman Kelulusan 2025/2026',
        'slug' => '2025-2026',
        'tanggal_pengumuman' => now()->subDay(),
        'pengambilan_mulai' => now()->addDays(3)->toDateString(),
        'pengambilan_selesai' => now()->addDays(7)->toDateString(),
        'tempat_pengambilan' => 'Ruang Tata Usaha',
        'dibuka' => true,
        'aktif' => true,
    ], $atribut));
}

function buatSiswaLulus(KelulusanPeriode $periode, array $atribut = []): Kelulusan
{
    return Kelulusan::create(array_merge([
        'kelulusan_periode_id' => $periode->id,
        'nisn' => '0091234561',
        'nama' => 'Siswa Uji',
        'tanggal_lahir' => '2010-03-12',
        'kelas' => 'IX A',
        'status' => 'lulus',
    ], $atribut));
}

it('menampilkan daftar angkatan kelulusan', function () {
    buatPeriodeKelulusan();

    $this->get('/kelulusan')
        ->assertOk()
        ->assertSee('Angkatan 2025/2026');
});

it('menampilkan hasil kelulusan untuk NISN dan tanggal lahir yang cocok', function () {
    $periode = buatPeriodeKelulusan();
    buatSiswaLulus($periode);

    $this->post('/kelulusan/2025-2026/cek', [
        'nisn' => '0091234561',
        'tanggal_lahir' => '2010-03-12',
    ])->assertRedirect('/kelulusan/2025-2026/hasil');

    $this->get('/kelulusan/2025-2026/hasil')
        ->assertOk()
        ->assertSee('LULUS')
        ->assertSee('Siswa Uji')
        ->assertSee('Ruang Tata Usaha');
});

it('menolak tanggal lahir yang tidak cocok', function () {
    $periode = buatPeriodeKelulusan();
    buatSiswaLulus($periode);

    $this->from('/kelulusan/2025-2026')
        ->post('/kelulusan/2025-2026/cek', [
            'nisn' => '0091234561',
            'tanggal_lahir' => '2011-01-01',
        ])
        ->assertRedirect('/kelulusan/2025-2026')
        ->assertSessionHas('galat');

    $this->get('/kelulusan/2025-2026/hasil')->assertRedirect('/kelulusan/2025-2026');
});

it('menolak akses ketika pengumuman belum dibuka', function () {
    $periode = buatPeriodeKelulusan(['dibuka' => false]);
    buatSiswaLulus($periode);

    $this->from('/kelulusan/2025-2026')
        ->post('/kelulusan/2025-2026/cek', [
            'nisn' => '0091234561',
            'tanggal_lahir' => '2010-03-12',
        ])
        ->assertRedirect('/kelulusan/2025-2026')
        ->assertSessionHas('galat');
});

it('menolak akses ketika jadwal pengumuman belum tiba', function () {
    $periode = buatPeriodeKelulusan(['tanggal_pengumuman' => now()->addWeek()]);
    buatSiswaLulus($periode);

    $this->from('/kelulusan/2025-2026')
        ->post('/kelulusan/2025-2026/cek', [
            'nisn' => '0091234561',
            'tanggal_lahir' => '2010-03-12',
        ])
        ->assertSessionHas('galat');
});

it('mengeluarkan siswa dari sesi pengumuman', function () {
    $periode = buatPeriodeKelulusan();
    buatSiswaLulus($periode);

    $this->post('/kelulusan/2025-2026/cek', [
        'nisn' => '0091234561',
        'tanggal_lahir' => '2010-03-12',
    ]);

    $this->post('/kelulusan/2025-2026/keluar')->assertRedirect('/kelulusan/2025-2026');
    $this->get('/kelulusan/2025-2026/hasil')->assertRedirect('/kelulusan/2025-2026');
});

it('menampilkan status tidak lulus beserta keterangannya', function () {
    $periode = buatPeriodeKelulusan();
    buatSiswaLulus($periode, ['status' => 'tidak_lulus', 'keterangan' => 'Silakan menghubungi wali kelas.']);

    $this->post('/kelulusan/2025-2026/cek', [
        'nisn' => '0091234561',
        'tanggal_lahir' => '2010-03-12',
    ]);

    $this->get('/kelulusan/2025-2026/hasil')
        ->assertOk()
        ->assertSee('TIDAK LULUS')
        ->assertSee('Silakan menghubungi wali kelas.');
});
