@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    <div class="kartu">
        <div class="kartu__isi">
            @if($audio)
                <p><x-ikon nama="musik" ukuran="18" /> Putar hymne {{ $identitas['nama'] ?? 'sekolah' }}:</p>
                <audio controls preload="none" style="width:100%;">
                    <source src="{{ $audio }}" type="audio/mpeg">
                    Peramban Anda tidak mendukung pemutar audio.
                </audio>
                <p style="margin-top:10px;">
                    <a class="tombol tombol--kecil tombol--garis" href="{{ $audio }}" download>
                        <x-ikon nama="unduh" ukuran="14" /> Unduh MP3
                    </a>
                </p>
            @else
                <div class="kosong">
                    <strong>Berkas hymne belum diunggah</strong>
                    Admin dapat mengunggah berkas MP3 melalui menu Pengaturan Website.
                </div>
            @endif

            @if($halaman?->isi)
                <hr>
                <div class="artikel">{!! $halaman->isi !!}</div>
            @endif
        </div>
    </div>
</div>
@endsection
