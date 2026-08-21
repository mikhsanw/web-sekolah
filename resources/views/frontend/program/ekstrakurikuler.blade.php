@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    @if($ekstrakurikuler->isEmpty())
        <div class="kartu"><div class="kosong">
            <strong>Belum ada data ekstrakurikuler</strong>
            Data akan tampil setelah admin menambahkannya.
        </div></div>
    @else
        <div class="grid-kartu">
            @foreach($ekstrakurikuler as $item)
                <article class="kartu-konten">
                    <div class="kartu-konten__gambar">
                        <img src="{{ $item->berkasUrl('gambar', asset($templateWeb.'/img/placeholder.svg')) }}"
                            alt="{{ $item->nama }}" loading="lazy">
                    </div>
                    <div class="kartu-konten__isi">
                        <h3 class="kartu-konten__judul">{{ $item->nama }}</h3>
                        <p class="kartu-konten__teks">{{ potongTeks($item->deskripsi, 150) }}</p>
                        <ul class="footer__daftar" style="margin-top:10px;font-size:.83rem;color:var(--teks-lembut);">
                            @if($item->pembina)<li><x-ikon nama="pengguna" ukuran="13" /> Pembina: {{ $item->pembina }}</li>@endif
                            @if($item->jadwal)<li><x-ikon nama="jam" ukuran="13" /> {{ $item->jadwal }}</li>@endif
                            @if($item->tempat)<li><x-ikon nama="lokasi" ukuran="13" /> {{ $item->tempat }}</li>@endif
                        </ul>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
