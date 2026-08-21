@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    <div class="kartu">
        <div class="kartu__isi">
            @if($gambar)
                <img src="{{ $gambar }}" alt="Denah {{ $identitas['nama'] ?? 'sekolah' }}">
            @endif

            @if($mapsEmbed)
                <div style="margin-top:16px;border-radius:8px;overflow:hidden;">
                    <iframe src="{{ $mapsEmbed }}" width="100%" height="380" style="border:0;" allowfullscreen
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi sekolah"></iframe>
                </div>
            @endif

            @if(! $gambar && ! $mapsEmbed)
                <div class="kosong">
                    <strong>Peta sekolah belum tersedia</strong>
                    Admin dapat mengunggah denah atau memasang URL embed Google Maps di menu Pengaturan Website.
                </div>
            @endif

            @if($halaman?->isi)
                <hr>
                <div class="artikel">{!! $halaman->isi !!}</div>
            @endif

            <hr>
            <p style="margin:0;font-size:.92rem;">
                <x-ikon nama="lokasi" ukuran="15" /> {{ $identitas['alamat'] ?? '-' }}
            </p>
        </div>
    </div>
</div>
@endsection
