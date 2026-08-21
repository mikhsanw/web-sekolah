@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    @include('frontend.partials.pesan')

    @if(! $periode)
        <div class="kartu"><div class="kosong">
            <strong>Belum ada periode SPMB</strong>
            Informasi pendaftaran akan diumumkan kemudian.
        </div></div>
    @else
        <div class="kartu">
            <div class="kartu__kepala">
                <h2 class="kartu__judul"><x-ikon nama="info" ukuran="17" /> {{ $periode->judul }}</h2>
                <span class="lencana lencana--{{ $periode->sedang_dibuka ? 'success' : 'secondary' }}">
                    {{ $periode->sedang_dibuka ? 'Pendaftaran dibuka' : 'Pendaftaran ditutup' }}
                </span>
            </div>
            <div class="kartu__isi">
                <ul class="daftar-rincian">
                    <li><span class="label">Tahun Ajaran</span><span>{{ $periode->tahun_ajaran }}</span></li>
                    @if($periode->tanggal_buka)
                        <li><span class="label">Mulai</span><span>{{ tanggalIndo($periode->tanggal_buka, 'dddd, D MMMM Y [pukul] HH:mm') }} WIB</span></li>
                    @endif
                    @if($periode->tanggal_tutup)
                        <li><span class="label">Berakhir</span><span>{{ tanggalIndo($periode->tanggal_tutup, 'dddd, D MMMM Y [pukul] HH:mm') }} WIB</span></li>
                    @endif
                    @if($periode->kuota)
                        <li><span class="label">Kuota</span><span>{{ $periode->kuota }} peserta didik</span></li>
                    @endif
                </ul>

                @if($periode->informasi)
                    <hr>
                    <div class="artikel">{!! $periode->informasi !!}</div>
                @endif

                @if($periode->syarat)
                    <hr>
                    <h3><x-ikon nama="dokumen" ukuran="17" /> Persyaratan</h3>
                    <div class="artikel">{!! $periode->syarat !!}</div>
                @endif

                @if($halaman?->isi)
                    <hr>
                    <div class="artikel">{!! $halaman->isi !!}</div>
                @endif
            </div>
            <div class="kartu__kaki">
                <a class="tombol" href="{{ route('web.spmb.pendaftaran') }}">
                    <x-ikon nama="daftar" ukuran="15" /> Menuju Pendaftaran
                </a>
            </div>
        </div>

        <h2 class="judul-bagian">Alur Pendaftaran</h2>
        <div class="langkah">
            <div class="langkah__item">
                <div class="langkah__nomor">1</div>
                <strong>Masuk</strong>
                <p style="margin:4px 0 0;font-size:.87rem;color:var(--teks-lembut);">Gunakan NISN dan tanggal lahir calon peserta didik.</p>
            </div>
            <div class="langkah__item">
                <div class="langkah__nomor">2</div>
                <strong>Isi Formulir</strong>
                <p style="margin:4px 0 0;font-size:.87rem;color:var(--teks-lembut);">Lengkapi asal sekolah, NIK, nama, jenis kelamin, agama, TTL, alamat, dan nomor HP.</p>
            </div>
            <div class="langkah__item">
                <div class="langkah__nomor">3</div>
                <strong>Cetak Bukti</strong>
                <p style="margin:4px 0 0;font-size:.87rem;color:var(--teks-lembut);">Simpan nomor pendaftaran dan cetak bukti pendaftaran.</p>
            </div>
            <div class="langkah__item">
                <div class="langkah__nomor">4</div>
                <strong>Verifikasi</strong>
                <p style="margin:4px 0 0;font-size:.87rem;color:var(--teks-lembut);">Panitia memverifikasi berkas dan mengumumkan hasilnya.</p>
            </div>
        </div>
    @endif
</div>
@endsection
