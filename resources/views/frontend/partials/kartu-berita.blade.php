@php
    $gambar = $berita->file?->link_public_stream ?? asset(config('master.app.web.template').'/img/placeholder.svg');
@endphp
<article class="berita-item">
    <a class="berita-item__gambar" href="{{ route('web.berita.show', $berita->slug) }}">
        <img src="{{ $gambar }}" alt="{{ $berita->judul }}" loading="lazy">
    </a>
    <div>
        <h3 class="berita-item__judul">
            <a href="{{ route('web.berita.show', $berita->slug) }}">{{ $berita->judul }}</a>
        </h3>
        <div class="berita-meta">
            <span><x-ikon nama="kalender" ukuran="14" /> {{ tanggalIndo($berita->tanggal ?? $berita->created_at, 'D MMMM Y') }}</span>
            <span><x-ikon nama="mata" ukuran="14" /> {{ (int) $berita->view }} kali dibaca</span>
        </div>
        <p class="berita-item__ringkas">{{ potongTeks($berita->isi, 180) }}</p>
        <a class="tombol tombol--kecil tombol--garis" href="{{ route('web.berita.show', $berita->slug) }}">
            Baca selengkapnya <x-ikon nama="panah-kanan" ukuran="14" />
        </a>
    </div>
</article>
