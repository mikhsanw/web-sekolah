@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    <div class="kartu">
        <div class="kartu__isi">
            @if($gambar)
                <img src="{{ $gambar }}" alt="Struktur Organisasi {{ $identitas['nama'] ?? '' }}" style="margin:0 auto;">
            @else
                <div class="kosong">
                    <strong>Bagan struktur organisasi belum diunggah</strong>
                    Admin dapat mengunggahnya melalui menu Pengaturan Website.
                </div>
            @endif

            @if($halaman?->isi)
                <hr>
                <div class="artikel">{!! $halaman->isi !!}</div>
            @endif
        </div>
    </div>

    @if($pimpinan->isNotEmpty())
        <h2 class="judul-bagian"><x-ikon nama="pengguna-grup" ukuran="20" /> Pejabat & Penanggung Jawab</h2>
        <div class="grid-kartu">
            @foreach($pimpinan as $orang)
                <div class="kartu-konten kartu-orang">
                    <img class="kartu-orang__foto"
                        src="{{ $orang->berkasUrl('foto', asset($templateWeb.'/img/avatar.svg')) }}" alt="{{ $orang->nama }}" loading="lazy">
                    <div class="kartu-orang__nama">{{ $orang->nama }}</div>
                    <div class="kartu-orang__jabatan">{{ $orang->jabatan }}</div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
