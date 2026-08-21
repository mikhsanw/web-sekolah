<?php

use App\Models\Agenda;
use App\Models\Kelulusan;
use App\Models\KelulusanPeriode;
use App\Models\SpmbPendaftar;
use App\Models\SpmbPeriode;
use App\Models\User;
use App\Services\BerkasExcel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    $this->superAdmin = User::where('email', 'superadmin@example.com')->firstOrFail();
});

it('membuka halaman index setiap modul website sekolah', function (string $url) {
    $this->actingAs($this->superAdmin)->get($url)->assertOk();
})->with([
    'admin/halaman',
    'admin/agenda',
    'admin/ekstrakurikuler',
    'admin/pegawai',
    'admin/prestasi',
    'admin/fasilitas',
    'admin/galeri',
    'admin/periode-kelulusan',
    'admin/kelulusan',
    'admin/periode-spmb',
    'admin/spmb',
    'admin/pengaturan',
]);

it('menolak tamu membuka modul backend', function () {
    $this->get('admin/kelulusan')->assertRedirect('/login');
});

it('menyimpan pengaturan website', function () {
    $this->actingAs($this->superAdmin)
        ->post('admin/pengaturan', [
            'nilai' => [
                'nama_sekolah' => 'SMPN 5 Mandau',
                'alamat' => 'Jl. Uji Coba No. 5',
                'whatsapp' => '081234567890',
            ],
        ])
        ->assertRedirect(route('pengaturan.index'));

    expect(pengaturan('alamat'))->toBe('Jl. Uji Coba No. 5')
        ->and(nomorWhatsapp())->toBe('6281234567890');
});

it('mengunduh template import kelulusan', function () {
    $this->actingAs($this->superAdmin)
        ->get(route('kelulusan.template'))
        ->assertOk()
        ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

it('mengimpor data kelulusan dari berkas excel', function () {
    $periode = KelulusanPeriode::where('tahun_ajaran', '2025/2026')->firstOrFail();

    $berkas = berkasKelulusanUji([
        ['0090000001', '2021001', 'Siswa Import Satu', '2010-01-15', 'IX A', 'lulus', '', '2026-06-10'],
        ['0090000002', '2021002', 'Siswa Import Dua', '2010-02-20', 'IX B', 'tidak_lulus', 'Perlu remedial', ''],
        ['', '', 'Tanpa NISN', '2010-03-01', 'IX C', 'lulus', '', ''],
    ]);

    $this->actingAs($this->superAdmin)
        ->post(route('kelulusan.import'), [
            'kelulusan_periode_id' => $periode->id,
            'berkas' => $berkas,
        ])
        ->assertSessionHas('sukses');

    expect(Kelulusan::where('nisn', '0090000001')->exists())->toBeTrue()
        ->and(Kelulusan::where('nisn', '0090000002')->firstOrFail()->status)->toBe('tidak_lulus')
        ->and(Kelulusan::where('nama', 'Tanpa NISN')->exists())->toBeFalse();

    $siswa = Kelulusan::where('nisn', '0090000001')->firstOrFail();
    expect($siswa->tanggal_lahir->toDateString())->toBe('2010-01-15');
});

it('memperbarui data lama hanya ketika opsi timpa dipilih', function () {
    $periode = KelulusanPeriode::where('tahun_ajaran', '2025/2026')->firstOrFail();

    Kelulusan::create([
        'kelulusan_periode_id' => $periode->id,
        'nisn' => '0090000003',
        'nama' => 'Nama Lama',
        'tanggal_lahir' => '2010-04-04',
        'status' => 'lulus',
    ]);

    $baris = [['0090000003', '', 'Nama Baru', '2010-04-04', 'IX A', 'lulus', '', '']];

    $this->actingAs($this->superAdmin)->post(route('kelulusan.import'), [
        'kelulusan_periode_id' => $periode->id,
        'berkas' => berkasKelulusanUji($baris),
    ]);

    expect(Kelulusan::where('nisn', '0090000003')->firstOrFail()->nama)->toBe('Nama Lama');

    $this->actingAs($this->superAdmin)->post(route('kelulusan.import'), [
        'kelulusan_periode_id' => $periode->id,
        'berkas' => berkasKelulusanUji($baris),
        'timpa' => 1,
    ]);

    expect(Kelulusan::where('nisn', '0090000003')->firstOrFail()->nama)->toBe('Nama Baru');
});

it('mengekspor pendaftar spmb ke excel', function () {
    $periode = SpmbPeriode::where('tahun_ajaran', '2026/2027')->firstOrFail();

    SpmbPendaftar::create([
        'spmb_periode_id' => $periode->id,
        'no_pendaftaran' => SpmbPendaftar::buatNomorPendaftaran($periode),
        'nisn' => '0091111111',
        'nik' => '1401011203100001',
        'nama' => 'Pendaftar Uji',
        'jenis_kelamin' => 'P',
        'agama' => 'Islam',
        'tempat_lahir' => 'Duri',
        'tanggal_lahir' => '2013-05-05',
        'alamat' => 'Jl. Uji',
        'no_hp' => '081234567890',
        'asal_sekolah' => 'SDN 1 Mandau',
        'status' => 'baru',
        'terdaftar_pada' => now(),
    ]);

    $response = $this->actingAs($this->superAdmin)->get(route('spmb.export'));

    $response->assertOk()
        ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    expect(strlen($response->streamedContent()))->toBeGreaterThan(0);
});

it('membaca tanggal serial excel menjadi format tanggal', function () {
    expect(BerkasExcel::keTanggal(40193))->toBe('2010-01-15')
        ->and(BerkasExcel::keTanggal('15/01/2010'))->toBe('2010-01-15')
        ->and(BerkasExcel::keTanggal('2010-01-15'))->toBe('2010-01-15')
        ->and(BerkasExcel::keTanggal(''))->toBeNull();
});

/**
 * Buat berkas .xlsx sementara berisi data kelulusan untuk pengujian impor.
 *
 * @param  array<int, array<int, string>>  $baris
 */
function berkasKelulusanUji(array $baris): UploadedFile
{
    $spreadsheet = new Spreadsheet;
    $lembar = $spreadsheet->getActiveSheet();
    $lembar->fromArray(['NISN', 'NIS', 'Nama', 'Tanggal Lahir', 'Kelas', 'Status', 'Keterangan', 'Tanggal Pengambilan'], null, 'A1');
    $lembar->fromArray($baris, null, 'A2');

    $path = tempnam(sys_get_temp_dir(), 'uji').'.xlsx';
    (new Xlsx($spreadsheet))->save($path);

    return new UploadedFile($path, 'kelulusan.xlsx', null, null, true);
}

it('mengembalikan JSON datatable untuk setiap modul', function (string $url) {
    $this->actingAs($this->superAdmin)
        ->get($url, ['X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk()
        ->assertJsonStructure(['data', 'recordsTotal', 'recordsFiltered']);
})->with([
    'admin/halaman',
    'admin/agenda',
    'admin/ekstrakurikuler',
    'admin/pegawai',
    'admin/prestasi',
    'admin/fasilitas',
    'admin/galeri',
    'admin/periode-kelulusan',
    'admin/kelulusan',
    'admin/periode-spmb',
    'admin/spmb',
    'admin/berita',
]);

it('melayani berkas datatable.js tiap modul', function () {
    $this->actingAs($this->superAdmin)
        ->get('js/backend/agenda/datatable.js')
        ->assertOk()
        ->assertHeader('content-type', 'application/javascript');
});

it('menjalankan siklus CRUD agenda dari panel admin', function () {
    $this->actingAs($this->superAdmin);

    $this->get(route('agenda.create'))->assertOk();

    $this->post(route('agenda.store'), [
        'judul' => 'Rapat Komite Sekolah',
        'jenis' => 'kegiatan',
        'tanggal_mulai' => '2026-09-01',
        'tanggal_selesai' => '2026-09-01',
        'waktu' => '09.00 WIB',
        'lokasi' => 'Aula',
        'deskripsi' => 'Rapat rutin komite.',
        'aktif' => 1,
    ])->assertOk()->assertJson(['status' => true]);

    $agenda = Agenda::where('judul', 'Rapat Komite Sekolah')->firstOrFail();
    expect($agenda->aktif)->toBeTrue();

    $this->get(route('agenda.edit', $agenda->id))->assertOk();
    $this->get(route('agenda.show', $agenda->id))->assertOk();

    $this->put(route('agenda.update', $agenda->id), [
        'judul' => 'Rapat Komite Sekolah (Revisi)',
        'jenis' => 'kegiatan',
        'tanggal_mulai' => '2026-09-02',
        'aktif' => 0,
    ])->assertOk()->assertJson(['status' => true]);

    $agenda->refresh();
    expect($agenda->judul)->toBe('Rapat Komite Sekolah (Revisi)')
        ->and($agenda->aktif)->toBeFalse();

    $this->delete(route('agenda.destroy', $agenda->id))->assertOk()->assertJson(['status' => true]);
    expect(Agenda::whereKey($agenda->id)->exists())->toBeFalse();
});

it('menolak agenda dengan tanggal selesai sebelum tanggal mulai', function () {
    $this->actingAs($this->superAdmin)
        ->post(route('agenda.store'), [
            'judul' => 'Agenda Tidak Valid',
            'jenis' => 'kegiatan',
            'tanggal_mulai' => '2026-09-10',
            'tanggal_selesai' => '2026-09-01',
        ])
        ->assertSessionHasErrors('tanggal_selesai');
});

it('menolak NISN kelulusan ganda pada periode yang sama', function () {
    $periode = KelulusanPeriode::where('tahun_ajaran', '2025/2026')->firstOrFail();

    $isi = [
        'kelulusan_periode_id' => $periode->id,
        'nisn' => '0098888888',
        'nama' => 'Siswa Duplikat',
        'tanggal_lahir' => '2010-03-12',
        'status' => 'lulus',
    ];

    $this->actingAs($this->superAdmin)->post(route('kelulusan.store'), $isi)->assertOk();
    $this->actingAs($this->superAdmin)->post(route('kelulusan.store'), $isi)->assertSessionHasErrors('nisn');
});
