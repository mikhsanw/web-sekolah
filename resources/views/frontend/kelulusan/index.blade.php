@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    @include('frontend.partials.pesan')

    <div class="kartu">
        <div class="kartu__isi">
            <p style="margin:0;">Pilih angkatan untuk melihat pengumuman kelulusan. Pengumuman diakses memakai
                <strong>NISN</strong> dan <strong>tanggal lahir</strong> masing-masing siswa.</p>
        </div>
    </div>

    @if($periode->isEmpty())
        <div class="kartu"><div class="kosong">
            <strong>Belum ada angkatan yang tersedia</strong>
            Panitia belum membuat periode pengumuman kelulusan.
        </div></div>
    @else
        <div class="grid-kartu">
            @foreach($periode as $item)
                <article class="kartu-konten">
                    <div class="kartu-konten__isi">
                        <span class="lencana lencana--{{ $item->sudah_diumumkan ? 'success' : 'secondary' }}">
                            {{ $item->sudah_diumumkan ? 'Sudah dibuka' : 'Belum dibuka' }}
                        </span>
                        <h3 class="kartu-konten__judul" style="margin-top:8px;">Angkatan {{ $item->tahun_ajaran }}</h3>
                        <p class="kartu-konten__teks">
                            {{ $item->judul }}<br>
                            <small>{{ $item->kelulusan_count }} siswa terdata</small>
                        </p>
                        @if($item->tanggal_pengumuman)
                            <div style="font-size:.82rem;color:var(--teks-lembut);margin-bottom:10px;">
                                <x-ikon nama="jam" ukuran="13" /> Diumumkan {{ tanggalIndo($item->tanggal_pengumuman, 'D MMMM Y, HH:mm') }} WIB
                            </div>
                        @endif
                        <a class="tombol tombol--blok {{ $item->sudah_diumumkan ? '' : 'tombol--netral' }}"
                            href="{{ route('web.kelulusan.periode', $item->slug) }}">
                            <x-ikon nama="kunci" ukuran="15" /> Cek Kelulusan
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
