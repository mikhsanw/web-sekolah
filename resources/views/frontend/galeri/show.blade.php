@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    <div class="kartu">
        <div class="kartu__isi">
            @if($galeri->kategori === 'video' && $galeri->video_embed)
                <div style="position:relative;padding-top:56.25%;border-radius:8px;overflow:hidden;">
                    <iframe src="{{ $galeri->video_embed }}" title="{{ $galeri->judul }}" allowfullscreen loading="lazy"
                        style="position:absolute;inset:0;width:100%;height:100%;border:0;"></iframe>
                </div>
            @else
                <img src="{{ $galeri->berkasUrl('gambar', asset($templateWeb.'/img/placeholder.svg')) }}" alt="{{ $galeri->judul }}"
                    style="margin:0 auto;border-radius:8px;">
            @endif

            <div class="berita-meta" style="margin-top:14px;">
                <span class="lencana lencana--primary">{{ config('sekolah.enum.kategori_galeri')[$galeri->kategori] ?? $galeri->kategori }}</span>
                @if($galeri->tanggal)<span><x-ikon nama="kalender" ukuran="14" /> {{ tanggalIndo($galeri->tanggal, 'D MMMM Y') }}</span>@endif
            </div>

            @if($galeri->deskripsi)
                <p style="margin-top:10px;">{{ $galeri->deskripsi }}</p>
            @endif
        </div>
    </div>

    @if($lainnya->isNotEmpty())
        <h2 class="judul-bagian">Dokumentasi Lainnya</h2>
        <div class="galeri-grid">
            @foreach($lainnya as $item)
                <a class="galeri-item" href="{{ route('web.galeri.show', $item->slug) }}">
                    <img src="{{ $item->berkasUrl('gambar', asset($templateWeb.'/img/placeholder.svg')) }}" alt="{{ $item->judul }}" loading="lazy">
                    <span class="galeri-item__label">{{ $item->judul }}</span>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
