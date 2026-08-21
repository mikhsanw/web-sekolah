<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\Berita;
use App\Models\Ekstrakurikuler;
use App\Models\Fasilitas;
use App\Models\Galeri;
use App\Models\Halaman;
use App\Models\Kelulusan;
use App\Models\KelulusanPeriode;
use App\Models\Pegawai;
use App\Models\Pengaturan;
use App\Models\Prestasi;
use App\Models\SpmbPeriode;
use Illuminate\Database\Seeder;

/**
 * Data awal website sekolah: pengaturan, halaman profil, dan contoh konten.
 * Seeder ini idempotent — aman dijalankan ulang.
 */
class SekolahSeeder extends Seeder
{
    public function run(): void
    {
        $this->pengaturan();
        $this->halaman();
        $this->agenda();
        $this->ekstrakurikuler();
        $this->pegawai();
        $this->prestasi();
        $this->fasilitas();
        $this->galeri();
        $this->berita();
        $this->kelulusan();
        $this->spmb();

        Pengaturan::bersihkanCache();
    }

    protected function pengaturan(): void
    {
        foreach (Pengaturan::definisi() as $item) {
            Pengaturan::firstOrCreate(
                ['kunci' => $item['kunci']],
                [
                    'kelompok' => $item['kelompok'],
                    'label' => $item['label'],
                    'tipe' => $item['tipe'],
                    'nilai' => $item['nilai'] ?? null,
                    'keterangan' => $item['keterangan'] ?? null,
                    'urutan' => $item['urutan'] ?? 0,
                ]
            );
        }
    }

    protected function halaman(): void
    {
        $nama = config('sekolah.identitas.nama');

        $daftar = [
            [
                'slug' => 'sejarah-singkat',
                'judul' => 'Sejarah Singkat',
                'kategori' => 'profil',
                'ringkasan' => 'Perjalanan '.$nama.' sejak berdiri hingga sekarang.',
                'isi' => '<p>'.$nama.' merupakan sekolah menengah pertama negeri yang berada di Kecamatan Mandau, Kabupaten Bengkalis, Provinsi Riau. Sekolah ini didirikan untuk memenuhi kebutuhan layanan pendidikan jenjang SMP bagi masyarakat di sekitar wilayah Mandau.</p><p>Silakan lengkapi naskah sejarah sekolah pada menu <strong>Konten Website &rarr; Halaman Statis</strong> di panel admin.</p>',
                'urutan' => 1,
            ],
            [
                'slug' => 'sambutan-kepala-sekolah',
                'judul' => 'Sambutan Kepala Sekolah',
                'kategori' => 'profil',
                'ringkasan' => 'Kata sambutan Kepala '.$nama.'.',
                'isi' => '<p>Assalamualaikum warahmatullahi wabarakatuh.</p><p>Puji syukur kita panjatkan ke hadirat Tuhan Yang Maha Esa. Website ini kami hadirkan sebagai media informasi dan komunikasi antara sekolah, peserta didik, orang tua, dan masyarakat.</p><p>Semoga kehadiran website ini membawa manfaat bagi kemajuan '.$nama.'.</p><p><em>Silakan ganti naskah sambutan ini melalui panel admin.</em></p>',
                'urutan' => 2,
            ],
            [
                'slug' => 'visi-dan-misi',
                'judul' => 'Visi dan Misi',
                'kategori' => 'profil',
                'ringkasan' => 'Visi dan misi '.$nama.'.',
                'isi' => '<h3>Visi</h3><p>Terwujudnya peserta didik yang beriman, berprestasi, berkarakter, dan peduli lingkungan.</p><h3>Misi</h3><ol><li>Menyelenggarakan pembelajaran yang aktif, kreatif, dan menyenangkan.</li><li>Menumbuhkan penghayatan nilai keagamaan dan budaya bangsa.</li><li>Mengembangkan potensi peserta didik melalui kegiatan ekstrakurikuler.</li><li>Mewujudkan lingkungan sekolah yang bersih, sehat, dan nyaman.</li><li>Meningkatkan kerja sama dengan orang tua dan masyarakat.</li></ol>',
                'urutan' => 3,
            ],
            [
                'slug' => 'logo-sekolah',
                'judul' => 'Logo Sekolah',
                'kategori' => 'profil',
                'ringkasan' => 'Makna lambang '.$nama.'.',
                'isi' => '<p>Halaman ini memuat lambang sekolah beserta maknanya. Unggah berkas logo melalui menu <strong>Pengaturan Website &rarr; Logo, Hymne &amp; Gambar</strong>, lalu tuliskan makna setiap unsur lambang di sini.</p>',
                'urutan' => 4,
            ],
            [
                'slug' => 'struktur-organisasi',
                'judul' => 'Struktur Organisasi',
                'kategori' => 'profil',
                'ringkasan' => 'Susunan organisasi dan tata kerja sekolah.',
                'isi' => '<p>Bagan struktur organisasi diunggah melalui menu <strong>Pengaturan Website</strong>. Daftar pejabat diambil otomatis dari data Guru &amp; Tendik yang memiliki jabatan.</p>',
                'urutan' => 5,
            ],
            [
                'slug' => 'hymne-sekolah',
                'judul' => 'Hymne Sekolah',
                'kategori' => 'profil',
                'ringkasan' => 'Lirik dan rekaman hymne sekolah.',
                'isi' => '<p>Unggah berkas MP3 hymne melalui menu <strong>Pengaturan Website</strong>, lalu tuliskan liriknya pada halaman ini.</p>',
                'urutan' => 6,
            ],
            [
                'slug' => 'kurikulum',
                'judul' => 'Kurikulum',
                'kategori' => 'program',
                'ringkasan' => 'Kurikulum yang diterapkan di '.$nama.'.',
                'isi' => '<p>'.$nama.' menerapkan Kurikulum Merdeka pada seluruh tingkat kelas. Struktur kurikulum terdiri atas pembelajaran intrakurikuler, projek penguatan profil pelajar Pancasila, dan ekstrakurikuler.</p><p><em>Lengkapi uraian kurikulum melalui panel admin.</em></p>',
                'urutan' => 1,
            ],
            [
                'slug' => 'informasi-siswa',
                'judul' => 'Informasi Siswa',
                'kategori' => 'kesiswaan',
                'ringkasan' => 'Data peserta didik dan layanan kesiswaan.',
                'isi' => '<p>Halaman ini memuat informasi jumlah peserta didik per tingkat, tata tertib sekolah, serta layanan bimbingan konseling.</p><p><em>Lengkapi melalui panel admin.</em></p>',
                'urutan' => 1,
            ],
            [
                'slug' => 'peta-sekolah',
                'judul' => 'Peta Sekolah',
                'kategori' => 'sarpras',
                'ringkasan' => 'Denah dan lokasi '.$nama.'.',
                'isi' => '<p>Unggah denah sekolah dan pasang URL embed Google Maps melalui menu <strong>Pengaturan Website</strong>.</p>',
                'urutan' => 1,
            ],
            [
                'slug' => 'informasi-spmb',
                'judul' => 'Informasi SPMB',
                'kategori' => 'spmb',
                'ringkasan' => 'Penjelasan tambahan seputar SPMB.',
                'isi' => '<p>Informasi utama SPMB (jadwal, kuota, persyaratan) diatur pada menu <strong>SPMB &rarr; Periode SPMB</strong>. Halaman ini dipakai untuk keterangan tambahan.</p>',
                'urutan' => 1,
            ],
        ];

        foreach ($daftar as $item) {
            Halaman::firstOrCreate(['slug' => $item['slug']], $item);
        }
    }

    protected function agenda(): void
    {
        $tahun = (int) date('Y');

        $daftar = [
            ['judul' => 'Awal Tahun Pelajaran', 'jenis' => 'agenda_tahunan', 'tanggal_mulai' => $tahun.'-07-14', 'lokasi' => 'Lapangan Sekolah'],
            ['judul' => 'Masa Pengenalan Lingkungan Sekolah', 'jenis' => 'kegiatan', 'tanggal_mulai' => $tahun.'-07-14', 'tanggal_selesai' => $tahun.'-07-16', 'lokasi' => 'Aula Sekolah'],
            ['judul' => 'Peringatan HUT Kemerdekaan RI', 'jenis' => 'kegiatan', 'tanggal_mulai' => $tahun.'-08-17', 'waktu' => '07.00 WIB', 'lokasi' => 'Lapangan Sekolah'],
            ['judul' => 'Asesmen Sumatif Tengah Semester', 'jenis' => 'ujian', 'tanggal_mulai' => $tahun.'-09-15', 'tanggal_selesai' => $tahun.'-09-20'],
            ['judul' => 'Libur Semester Ganjil', 'jenis' => 'libur', 'tanggal_mulai' => $tahun.'-12-22', 'tanggal_selesai' => $tahun.'-12-31'],
        ];

        foreach ($daftar as $item) {
            Agenda::firstOrCreate(
                ['judul' => $item['judul']],
                array_merge(['aktif' => true, 'deskripsi' => null], $item)
            );
        }
    }

    protected function ekstrakurikuler(): void
    {
        $daftar = [
            ['nama' => 'Pramuka', 'pembina' => '-', 'jadwal' => 'Jumat, 14.00 - 16.00', 'tempat' => 'Lapangan Sekolah', 'urutan' => 1],
            ['nama' => 'Palang Merah Remaja', 'pembina' => '-', 'jadwal' => 'Sabtu, 08.00 - 10.00', 'tempat' => 'Ruang UKS', 'urutan' => 2],
            ['nama' => 'Olahraga (Futsal & Voli)', 'pembina' => '-', 'jadwal' => 'Rabu, 15.00 - 17.00', 'tempat' => 'Lapangan Sekolah', 'urutan' => 3],
            ['nama' => 'Seni Tari dan Musik', 'pembina' => '-', 'jadwal' => 'Kamis, 14.30 - 16.30', 'tempat' => 'Aula Sekolah', 'urutan' => 4],
            ['nama' => 'Rohis', 'pembina' => '-', 'jadwal' => 'Jumat, 13.00 - 14.00', 'tempat' => 'Musala Sekolah', 'urutan' => 5],
        ];

        foreach ($daftar as $item) {
            Ekstrakurikuler::firstOrCreate(
                ['nama' => $item['nama']],
                array_merge(['aktif' => true, 'deskripsi' => 'Kegiatan ekstrakurikuler '.$item['nama'].'. Lengkapi deskripsi melalui panel admin.'], $item)
            );
        }
    }

    protected function pegawai(): void
    {
        $daftar = [
            ['nama' => 'Nama Kepala Sekolah', 'jenis' => 'guru', 'jabatan' => 'Kepala Sekolah', 'mata_pelajaran' => null, 'urutan' => 1],
            ['nama' => 'Nama Wakil Kurikulum', 'jenis' => 'guru', 'jabatan' => 'Wakil Kepala Bidang Kurikulum', 'mata_pelajaran' => 'Matematika', 'urutan' => 2],
            ['nama' => 'Nama Wakil Kesiswaan', 'jenis' => 'guru', 'jabatan' => 'Wakil Kepala Bidang Kesiswaan', 'mata_pelajaran' => 'IPA', 'urutan' => 3],
            ['nama' => 'Nama Guru Bahasa Indonesia', 'jenis' => 'guru', 'jabatan' => 'Guru Mata Pelajaran', 'mata_pelajaran' => 'Bahasa Indonesia', 'urutan' => 4],
            ['nama' => 'Nama Kepala Tata Usaha', 'jenis' => 'tendik', 'jabatan' => 'Kepala Tata Usaha', 'urutan' => 1],
            ['nama' => 'Nama Petugas Perpustakaan', 'jenis' => 'tendik', 'jabatan' => 'Pustakawan', 'urutan' => 2],
        ];

        foreach ($daftar as $item) {
            Pegawai::firstOrCreate(
                ['nama' => $item['nama']],
                array_merge(['aktif' => true, 'jenis_kelamin' => 'L'], $item)
            );
        }
    }

    protected function prestasi(): void
    {
        $tahun = (int) date('Y');

        $daftar = [
            ['judul' => 'Juara 1 Lomba Cerdas Cermat Tingkat Kecamatan', 'kategori' => 'siswa', 'nama_peraih' => 'Tim LCC Sekolah', 'tingkat' => 'kecamatan', 'peringkat' => 'Juara 1', 'tahun' => $tahun],
            ['judul' => 'Juara 2 Olimpiade Sains Nasional Tingkat Kabupaten', 'kategori' => 'siswa', 'nama_peraih' => 'Peserta Didik Kelas IX', 'tingkat' => 'kabupaten', 'peringkat' => 'Juara 2', 'tahun' => $tahun],
            ['judul' => 'Guru Berprestasi Tingkat Kabupaten', 'kategori' => 'guru', 'nama_peraih' => 'Nama Guru', 'tingkat' => 'kabupaten', 'peringkat' => 'Juara 3', 'tahun' => $tahun],
        ];

        foreach ($daftar as $item) {
            Prestasi::firstOrCreate(
                ['judul' => $item['judul']],
                array_merge(['deskripsi' => null, 'penyelenggara' => null], $item)
            );
        }
    }

    protected function fasilitas(): void
    {
        $daftar = [
            ['nama' => 'Ruang Kelas', 'jumlah' => 12, 'kondisi' => 'baik', 'urutan' => 1],
            ['nama' => 'Perpustakaan', 'jumlah' => 1, 'kondisi' => 'baik', 'urutan' => 2],
            ['nama' => 'Laboratorium IPA', 'jumlah' => 1, 'kondisi' => 'baik', 'urutan' => 3],
            ['nama' => 'Laboratorium Komputer', 'jumlah' => 1, 'kondisi' => 'baik', 'urutan' => 4],
            ['nama' => 'Musala', 'jumlah' => 1, 'kondisi' => 'baik', 'urutan' => 5],
            ['nama' => 'Ruang UKS', 'jumlah' => 1, 'kondisi' => 'baik', 'urutan' => 6],
            ['nama' => 'Lapangan Olahraga', 'jumlah' => 1, 'kondisi' => 'baik', 'urutan' => 7],
        ];

        foreach ($daftar as $item) {
            Fasilitas::firstOrCreate(
                ['nama' => $item['nama']],
                array_merge(['aktif' => true, 'deskripsi' => null], $item)
            );
        }
    }

    protected function galeri(): void
    {
        $daftar = [
            ['judul' => 'Upacara Bendera Hari Senin', 'kategori' => 'foto', 'urutan' => 1],
            ['judul' => 'Kegiatan Belajar di Laboratorium IPA', 'kategori' => 'foto', 'urutan' => 2],
            ['judul' => 'Latihan Ekstrakurikuler Pramuka', 'kategori' => 'foto', 'urutan' => 3],
        ];

        foreach ($daftar as $item) {
            Galeri::firstOrCreate(
                ['judul' => $item['judul']],
                array_merge(['aktif' => true, 'tanggal' => now()->toDateString(), 'deskripsi' => null], $item)
            );
        }
    }

    protected function berita(): void
    {
        $nama = config('sekolah.identitas.nama');

        $daftar = [
            [
                'judul' => 'Selamat Datang di Website Resmi '.$nama,
                'isi' => '<p>Website resmi '.$nama.' hadir sebagai pusat informasi kegiatan sekolah, profil, agenda, prestasi, pengumuman kelulusan, dan pendaftaran murid baru (SPMB).</p><p>Silakan kelola konten melalui panel admin pada menu <strong>Konten Website</strong>.</p>',
            ],
            [
                'judul' => 'Panduan Singkat Penggunaan Website Sekolah',
                'isi' => '<p>Berita, agenda, galeri, dan prestasi dikelola melalui menu <strong>Konten Website</strong>. Data guru dan tenaga kependidikan berada pada menu <strong>Profil Sekolah</strong>.</p><p>Identitas sekolah, logo, hymne, serta tautan media sosial diatur pada menu <strong>Pengaturan Website</strong>.</p>',
            ],
        ];

        foreach ($daftar as $index => $item) {
            Berita::firstOrCreate(
                ['judul' => $item['judul']],
                array_merge($item, [
                    'tanggal' => now()->subDays($index),
                    'view' => 0,
                ])
            );
        }
    }

    protected function kelulusan(): void
    {
        $periode = KelulusanPeriode::firstOrCreate(
            ['tahun_ajaran' => '2025/2026'],
            [
                'judul' => 'Pengumuman Kelulusan Tahun Ajaran 2025/2026',
                'slug' => '2025-2026',
                'tanggal_pengumuman' => '2026-06-05 10:00:00',
                'pengambilan_mulai' => '2026-06-08',
                'pengambilan_selesai' => '2026-06-12',
                'tempat_pengambilan' => 'Ruang Tata Usaha',
                'keterangan' => 'Pengambilan administrasi wajib membawa kartu pelajar dan bukti bebas pinjaman perpustakaan.',
                'dibuka' => false,
                'aktif' => true,
            ]
        );

        KelulusanPeriode::firstOrCreate(
            ['tahun_ajaran' => '2026/2027'],
            [
                'judul' => 'Pengumuman Kelulusan Tahun Ajaran 2026/2027',
                'slug' => '2026-2027',
                'dibuka' => false,
                'aktif' => true,
            ]
        );

        $siswa = [
            ['nisn' => '0091234561', 'nama' => 'Contoh Siswa Lulus', 'tanggal_lahir' => '2010-03-12', 'kelas' => 'IX A', 'status' => 'lulus'],
            ['nisn' => '0091234562', 'nama' => 'Contoh Siswa Kedua', 'tanggal_lahir' => '2010-08-25', 'kelas' => 'IX B', 'status' => 'lulus'],
        ];

        foreach ($siswa as $item) {
            Kelulusan::firstOrCreate(
                ['kelulusan_periode_id' => $periode->id, 'nisn' => $item['nisn']],
                array_merge($item, ['kelulusan_periode_id' => $periode->id])
            );
        }
    }

    protected function spmb(): void
    {
        SpmbPeriode::firstOrCreate(
            ['tahun_ajaran' => '2026/2027'],
            [
                'judul' => 'SPMB Tahun Ajaran 2026/2027',
                'slug' => 'spmb-2026-2027',
                'tanggal_buka' => '2026-05-01 08:00:00',
                'tanggal_tutup' => '2026-06-30 23:59:00',
                'kuota' => 128,
                'informasi' => '<p>Seleksi Penerimaan Murid Baru (SPMB) '.config('sekolah.identitas.nama').' tahun ajaran 2026/2027 dilaksanakan secara daring melalui website sekolah.</p>',
                'syarat' => '<ol><li>Fotokopi akta kelahiran</li><li>Fotokopi kartu keluarga</li><li>Fotokopi ijazah/surat keterangan lulus SD/MI</li><li>Pas foto berwarna ukuran 3x4 sebanyak 2 lembar</li></ol>',
                'dibuka' => true,
                'aktif' => true,
            ]
        );
    }
}
