@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    <div style="max-width:680px;margin:0 auto;">
        <div class="hasil-kelulusan hasil-kelulusan--{{ $siswa->status }}">
            <p style="margin:0 0 6px;opacity:.9;">Dinyatakan</p>
            <h2>{{ $siswa->nama_status }}</h2>
            <p>{{ $identitas['nama'] ?? '' }} — Tahun Ajaran {{ $periode->tahun_ajaran }}</p>
        </div>

        <div class="kartu" style="margin-top:18px;">
            <div class="kartu__kepala"><h2 class="kartu__judul"><x-ikon nama="pengguna" ukuran="17" /> Data Siswa</h2></div>
            <div class="kartu__isi">
                <ul class="daftar-rincian">
                    <li><span class="label">Nama</span><span><strong>{{ $siswa->nama }}</strong></span></li>
                    <li><span class="label">NISN</span><span>{{ $siswa->nisn }}</span></li>
                    @if($siswa->nis)<li><span class="label">NIS</span><span>{{ $siswa->nis }}</span></li>@endif
                    @if($siswa->kelas)<li><span class="label">Kelas</span><span>{{ $siswa->kelas }}</span></li>@endif
                    <li><span class="label">Tanggal Lahir</span><span>{{ tanggalIndo($siswa->tanggal_lahir, 'D MMMM Y') }}</span></li>
                    <li><span class="label">Status Kelulusan</span>
                        <span><span class="lencana lencana--{{ $siswa->warna_status }}">{{ $siswa->nama_status }}</span></span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="kartu">
            <div class="kartu__kepala"><h2 class="kartu__judul"><x-ikon nama="dokumen" ukuran="17" /> Pengambilan Administrasi</h2></div>
            <div class="kartu__isi">
                <ul class="daftar-rincian">
                    <li><span class="label">Tanggal Pengambilan</span>
                        <span>{{ $siswa->info_pengambilan ?? 'Akan diinformasikan kemudian' }}</span>
                    </li>
                    @if($periode->tempat_pengambilan)
                        <li><span class="label">Tempat</span><span>{{ $periode->tempat_pengambilan }}</span></li>
                    @endif
                </ul>

                @if($siswa->keterangan)
                    <div class="alert alert--info" style="margin-top:12px;">{{ $siswa->keterangan }}</div>
                @endif
                @if($periode->keterangan)
                    <div style="font-size:.88rem;color:var(--teks-lembut);margin-top:10px;">{!! nl2br(e($periode->keterangan)) !!}</div>
                @endif
            </div>
            <div class="kartu__kaki tanpa-cetak" style="display:flex;gap:8px;flex-wrap:wrap;">
                <button class="tombol tombol--kecil" type="button" data-cetak>
                    <x-ikon nama="cetak" ukuran="14" /> Cetak Hasil
                </button>
                <form method="post" action="{{ route('web.kelulusan.keluar', $periode->slug) }}">
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
