<?php

use App\Models\SpmbPendaftar;
use App\Models\SpmbPeriode;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function buatPeriodeSpmb(array $atribut = []): SpmbPeriode
{
    return SpmbPeriode::create(array_merge([
        'tahun_ajaran' => '2026/2027',
        'judul' => 'SPMB 2026/2027',
        'slug' => 'spmb-2026-2027',
        'tanggal_buka' => now()->subDay(),
        'tanggal_tutup' => now()->addMonth(),
        'kuota' => 128,
        'dibuka' => true,
        'aktif' => true,
    ], $atribut));
}

/**
 * @return array<string, string>
 */
function isianFormulir(array $atribut = []): array
{
    return array_merge([
        'asal_sekolah' => 'SD Negeri 1 Mandau',
        'nisn' => '0091234561',
        'nik' => '1401011203100001',
        'nama' => 'Calon Peserta Didik',
        'jenis_kelamin' => 'L',
        'agama' => 'Islam',
        'tempat_lahir' => 'Duri',
        'tanggal_lahir' => '2013-03-12',
        'alamat' => 'Jl. Contoh No. 1, Mandau',
        'no_hp' => '081234567890',
        'nama_orang_tua' => 'Nama Orang Tua',
    ], $atribut);
}

it('mengarahkan pendaftar baru ke formulir setelah masuk', function () {
    buatPeriodeSpmb();

    $this->post('/spmb/pendaftaran', [
        'nisn' => '0091234561',
        'tanggal_lahir' => '2013-03-12',
    ])->assertRedirect('/spmb/formulir');

    $this->get('/spmb/formulir')->assertOk()->assertSee('0091234561');
});

it('menyimpan pendaftaran dan menerbitkan nomor pendaftaran', function () {
    buatPeriodeSpmb();

    $this->post('/spmb/pendaftaran', ['nisn' => '0091234561', 'tanggal_lahir' => '2013-03-12']);
    $this->post('/spmb/formulir', isianFormulir())->assertRedirect('/spmb/bukti-pendaftaran');

    $pendaftar = SpmbPendaftar::firstOrFail();

    expect($pendaftar->nama)->toBe('Calon Peserta Didik')
        ->and($pendaftar->no_pendaftaran)->toStartWith('SPMB-2026-')
        ->and($pendaftar->status)->toBe('baru')
        ->and($pendaftar->terdaftar_pada)->not->toBeNull();

    $this->get('/spmb/bukti-pendaftaran')->assertOk()->assertSee($pendaftar->no_pendaftaran);
});

it('mengunci NISN dan tanggal lahir dari sesi meski form dimanipulasi', function () {
    buatPeriodeSpmb();

    $this->post('/spmb/pendaftaran', ['nisn' => '0091234561', 'tanggal_lahir' => '2013-03-12']);
    $this->post('/spmb/formulir', isianFormulir([
        'nisn' => '0099999999',
        'tanggal_lahir' => '2000-01-01',
    ]))->assertRedirect('/spmb/bukti-pendaftaran');

    $pendaftar = SpmbPendaftar::firstOrFail();

    expect($pendaftar->nisn)->toBe('0091234561')
        ->and($pendaftar->tanggal_lahir->toDateString())->toBe('2013-03-12');
});

it('menolak masuk bila tanggal lahir tidak cocok dengan data terdaftar', function () {
    buatPeriodeSpmb();

    $this->post('/spmb/pendaftaran', ['nisn' => '0091234561', 'tanggal_lahir' => '2013-03-12']);
    $this->post('/spmb/formulir', isianFormulir());
    $this->post('/spmb/keluar');

    $this->from('/spmb/pendaftaran')
        ->post('/spmb/pendaftaran', ['nisn' => '0091234561', 'tanggal_lahir' => '2012-01-01'])
        ->assertRedirect('/spmb/pendaftaran')
        ->assertSessionHas('galat');
});

it('membuka kembali bukti pendaftaran bagi pendaftar lama', function () {
    buatPeriodeSpmb();

    $this->post('/spmb/pendaftaran', ['nisn' => '0091234561', 'tanggal_lahir' => '2013-03-12']);
    $this->post('/spmb/formulir', isianFormulir());
    $this->post('/spmb/keluar');

    $this->post('/spmb/pendaftaran', ['nisn' => '0091234561', 'tanggal_lahir' => '2013-03-12'])
        ->assertRedirect('/spmb/bukti-pendaftaran');
});

it('menolak pendaftaran ketika periode ditutup', function () {
    buatPeriodeSpmb(['dibuka' => false]);

    $this->from('/spmb/pendaftaran')
        ->post('/spmb/pendaftaran', ['nisn' => '0091234561', 'tanggal_lahir' => '2013-03-12'])
        ->assertSessionHas('galat');

    expect(SpmbPendaftar::count())->toBe(0);
});

it('memvalidasi isian formulir yang tidak lengkap', function () {
    buatPeriodeSpmb();

    $this->post('/spmb/pendaftaran', ['nisn' => '0091234561', 'tanggal_lahir' => '2013-03-12']);

    $this->post('/spmb/formulir', isianFormulir(['nik' => '123', 'nama' => '']))
        ->assertSessionHasErrors(['nik', 'nama']);

    expect(SpmbPendaftar::count())->toBe(0);
});

it('mencegah pengubahan data setelah diverifikasi panitia', function () {
    buatPeriodeSpmb();

    $this->post('/spmb/pendaftaran', ['nisn' => '0091234561', 'tanggal_lahir' => '2013-03-12']);
    $this->post('/spmb/formulir', isianFormulir());

    SpmbPendaftar::firstOrFail()->update(['status' => 'diverifikasi']);

    $this->get('/spmb/formulir')->assertRedirect('/spmb/bukti-pendaftaran');
    $this->post('/spmb/formulir', isianFormulir(['nama' => 'Nama Diubah']))->assertRedirect('/spmb/bukti-pendaftaran');

    expect(SpmbPendaftar::firstOrFail()->nama)->toBe('Calon Peserta Didik');
});
