@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    <div style="max-width:720px;margin:0 auto;">
        @include('frontend.partials.pesan')

        <div class="kartu">
            <div class="kartu__kepala">
                <h2 class="kartu__judul"><x-ikon nama="dokumen" ukuran="17" /> Bukti Pendaftaran SPMB</h2>
                <span class="lencana lencana--{{ $pendaftar->warna_status }}">{{ $pendaftar->nama_status }}</span>
            </div>
            <div class="kartu__isi">
                <div style="text-align:center;margin-bottom:16px;">
                    <div style="font-size:.82rem;color:var(--teks-lembut);">Nomor Pendaftaran</div>
                    <div style="font-size:1.6rem;font-weight:800;letter-spacing:1px;color:var(--warna-utama);">
                        {{ $pendaftar->no_pendaftaran }}
                    </div>
                </div>

                <ul class="daftar-rincian">
                    <li><span class="label">Tahun Ajaran</span><span>{{ $pendaftar->periode?->tahun_ajaran }}</span></li>
                    <li><span class="label">Nama Lengkap</span><span><strong>{{ $pendaftar->nama }}</strong></span></li>
                    <li><span class="label">NISN</span><span>{{ $pendaftar->nisn }}</span></li>
                    <li><span class="label">NIK</span><span>{{ $pendaftar->nik }}</span></li>
                    <li><span class="label">Jenis Kelamin</span><span>{{ $pendaftar->nama_jenis_kelamin }}</span></li>
                    <li><span class="label">Agama</span><span>{{ $pendaftar->agama }}</span></li>
                    <li><span class="label">Tempat, Tanggal Lahir</span><span>{{ $pendaftar->ttl }}</span></li>
                    <li><span class="label">Alamat</span><span>{{ $pendaftar->alamat }}</span></li>
                    <li><span class="label">Nomor HP</span><span>{{ $pendaftar->no_hp }}</span></li>
                    <li><span class="label">Asal Sekolah</span><span>{{ $pendaftar->asal_sekolah }}</span></li>
                    @if($pendaftar->nama_orang_tua)
                        <li><span class="label">Orang Tua/Wali</span><span>{{ $pendaftar->nama_orang_tua }}</span></li>
                    @endif
                    <li><span class="label">Waktu Pendaftaran</span><span>{{ tanggalIndo($pendaftar->terdaftar_pada, 'dddd, D MMMM Y HH:mm') }} WIB</span></li>
                </ul>

                @if($pendaftar->catatan)
                    <div class="alert alert--info" style="margin-top:14px;">{{ $pendaftar->catatan }}</div>
                @endif

                <p style="margin-top:16px;font-size:.84rem;color:var(--teks-lembut);">
                    Simpan atau cetak bukti ini. Tunjukkan kepada panitia saat verifikasi berkas.
                </p>
            </div>
            <div class="kartu__kaki tanpa-cetak" style="display:flex;gap:8px;flex-wrap:wrap;">
                <button class="tombol tombol--kecil" type="button" data-cetak>
                    <x-ikon nama="cetak" ukuran="14" /> Cetak
                </button>
                @if($pendaftar->status === 'baru')
                    <a class="tombol tombol--kecil tombol--garis" href="{{ route('web.spmb.formulir') }}">Ubah Data</a>
                @endif
                <form method="post" action="{{ route('web.spmb.keluar') }}" data-konfirmasi="Keluar dari halaman pendaftaran?">
                    @csrf
                    <button class="tombol tombol--kecil tombol--netral" type="submit">
                        <x-ikon nama="keluar" ukuran="14" /> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
