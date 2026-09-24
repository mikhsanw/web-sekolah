<?php

/**
 * Konfigurasi Website Sekolah
 *
 * Nilai di sini hanya dipakai sebagai default / fallback.
 * Nilai yang dipakai aplikasi diambil dari tabel `pengaturans`
 * (lihat App\Models\Pengaturan dan helper pengaturan()).
 */
return [
    /*
    |--------------------------------------------------------------------------
    | Identitas Sekolah
    |--------------------------------------------------------------------------
    */
    'identitas' => [
        'nama' => 'SMPN 5 Mandau',
        'nama_panjang' => 'UPT SMP Negeri 5 Mandau',
        'singkatan' => 'SMPN 5 Mandau',
        'npsn' => '-',
        'jenjang' => 'SMP',
        'status' => 'Negeri',
        'akreditasi' => '-',
        'kepala_sekolah' => '-',
        'nip_kepala_sekolah' => '-',
        'alamat' => 'Jl. Tribrata Kelurahan Babussalam - DURI, Kec. Mandau, Kab. Bengkalis',
        'kelurahan' => 'Babussalam',
        'kecamatan' => 'Mandau',
        'kabupaten' => 'Bengkalis',
        'provinsi' => 'Riau',
        'kode_pos' => '-',
        'telepon' => '-',
        'email' => '-',
        'moto' => 'Berprestasi, Berkarakter, Berbudaya Lingkungan',
    ],

    /*
    |--------------------------------------------------------------------------
    | Kontak & Media Sosial (dipakai di footer dan halaman kontak)
    |--------------------------------------------------------------------------
    */
    'kontak' => [
        'facebook' => '',
        'instagram' => '',
        'youtube' => '',
        'whatsapp' => '',
        'maps_embed' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tampilan
    |--------------------------------------------------------------------------
    */
    'tampilan' => [
        'warna_utama' => '#0f5132',
        'warna_aksen' => '#f0b429',
        'berita_beranda' => 4,
        'galeri_beranda' => 6,
        'agenda_beranda' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Navigasi Website
    |--------------------------------------------------------------------------
    | Kerangka menu halaman depan. Item bertanda 'dinamis' diisi dari database
    | oleh App\Http\Middleware\Frontend (mis. daftar angkatan kelulusan).
    */
    'navigasi' => [
        ['label' => 'Beranda', 'rute' => 'web.beranda'],
        ['label' => 'Profil', 'anak' => [
            ['label' => 'Sejarah Singkat', 'rute' => 'web.profil', 'params' => ['halaman' => 'sejarah-singkat']],
            ['label' => 'Sambutan Kepala Sekolah', 'rute' => 'web.profil', 'params' => ['halaman' => 'sambutan-kepala-sekolah']],
            ['label' => 'Visi dan Misi', 'rute' => 'web.profil', 'params' => ['halaman' => 'visi-dan-misi']],
            ['label' => 'Logo Sekolah', 'rute' => 'web.profil', 'params' => ['halaman' => 'logo-sekolah']],
            ['label' => 'Struktur Organisasi', 'rute' => 'web.struktur'],
            ['label' => 'Hymne Sekolah', 'rute' => 'web.hymne'],
        ]],
        ['label' => 'Program Sekolah', 'anak' => [
            ['label' => 'Agenda Tahunan', 'rute' => 'web.agenda'],
            ['label' => 'Ekstrakurikuler', 'rute' => 'web.ekstrakurikuler'],
            ['label' => 'Kurikulum', 'rute' => 'web.profil', 'params' => ['halaman' => 'kurikulum']],
        ]],
        ['label' => 'GTK', 'anak' => [
            ['label' => 'Informasi Guru', 'rute' => 'web.guru'],
            ['label' => 'Informasi Tendik', 'rute' => 'web.tendik'],
            ['label' => 'Prestasi Guru', 'rute' => 'web.prestasi', 'params' => ['kategori' => 'guru']],
        ]],
        ['label' => 'Kesiswaan', 'anak' => [
            ['label' => 'Prestasi Siswa', 'rute' => 'web.prestasi', 'params' => ['kategori' => 'siswa']],
            ['label' => 'Informasi Siswa', 'rute' => 'web.profil', 'params' => ['halaman' => 'informasi-siswa']],
        ]],
        ['label' => 'Sarana Prasarana', 'anak' => [
            ['label' => 'Peta Sekolah', 'rute' => 'web.peta'],
            ['label' => 'Fasilitas Sekolah', 'rute' => 'web.fasilitas'],
        ]],
        ['label' => 'Kelulusan', 'rute' => 'web.kelulusan.index', 'dinamis' => 'kelulusan'],
        ['label' => 'SPMB', 'anak' => [
            ['label' => 'Pendaftaran', 'rute' => 'web.spmb.pendaftaran'],
            ['label' => 'Informasi SPMB', 'rute' => 'web.spmb.informasi'],
        ]],
        ['label' => 'Berita', 'rute' => 'web.berita.index'],
        ['label' => 'Galeri', 'rute' => 'web.galeri.index'],
        ['label' => 'Kontak', 'rute' => 'web.kontak'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Enum konten
    |--------------------------------------------------------------------------
    */
    'enum' => [
        'kategori_halaman' => [
            'profil' => 'Profil',
            'program' => 'Program Sekolah',
            'kesiswaan' => 'Kesiswaan',
            'sarpras' => 'Sarana Prasarana',
            'spmb' => 'SPMB',
            'lainnya' => 'Lainnya',
        ],
        'jenis_agenda' => [
            'agenda_tahunan' => 'Agenda Tahunan',
            'kegiatan' => 'Kegiatan',
            'ujian' => 'Ujian',
            'libur' => 'Libur',
        ],
        'warna_agenda' => [
            'agenda_tahunan' => 'primary',
            'kegiatan' => 'success',
            'ujian' => 'warning',
            'libur' => 'danger',
        ],
        'jenis_pegawai' => [
            'guru' => 'Guru',
            'tendik' => 'Tenaga Kependidikan',
        ],
        'jenis_kelamin' => [
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
        ],
        'agama' => [
            'Islam' => 'Islam',
            'Kristen' => 'Kristen',
            'Katolik' => 'Katolik',
            'Hindu' => 'Hindu',
            'Buddha' => 'Buddha',
            'Konghucu' => 'Konghucu',
        ],
        'kategori_prestasi' => [
            'siswa' => 'Prestasi Siswa',
            'guru' => 'Prestasi Guru',
        ],
        'tingkat_prestasi' => [
            'sekolah' => 'Sekolah',
            'kecamatan' => 'Kecamatan',
            'kabupaten' => 'Kabupaten/Kota',
            'provinsi' => 'Provinsi',
            'nasional' => 'Nasional',
            'internasional' => 'Internasional',
        ],
        'kategori_galeri' => [
            'foto' => 'Foto',
            'video' => 'Video',
        ],
        'kondisi_fasilitas' => [
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
        ],
        'status_kelulusan' => [
            'lulus' => 'LULUS',
            'tidak_lulus' => 'TIDAK LULUS',
        ],
        'warna_kelulusan' => [
            'lulus' => 'success',
            'tidak_lulus' => 'danger',
        ],
        'status_spmb' => [
            'baru' => 'Menunggu Verifikasi',
            'diverifikasi' => 'Terverifikasi',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
        ],
        'warna_spmb' => [
            'baru' => 'warning',
            'diverifikasi' => 'info',
            'diterima' => 'success',
            'ditolak' => 'danger',
        ],
    ],
];
