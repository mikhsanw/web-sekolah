@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    <div class="tata-letak">
        <article>
            <div class="kartu">
                @if($berita->file)
                    <img class="berita-utama__gambar" src="{{ $berita->file->link_public_stream }}" alt="{{ $berita->judul }}">
                @endif
                <div class="kartu__isi">
                    <h1 style="font-size:1.55rem;">{{ $berita->judul }}</h1>
                    <div class="berita-meta">
                        <span><x-ikon nama="kalender" ukuran="14" /> {{ tanggalIndo($berita->tanggal ?? $berita->created_at) }}</span>
                        <span><x-ikon nama="mata" ukuran="14" /> {{ (int) $berita->view }} kali dibaca</span>
                    </div>
                    <hr>
                    <div class="artikel">{!! $berita->isi !!}</div>

                    <hr>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
                        <span style="font-size:.85rem;color:var(--teks-lembut);">Bagikan:</span>
                        <a class="tombol tombol--kecil tombol--netral" target="_blank" rel="noopener"
                            href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}">
                            <x-ikon nama="facebook" ukuran="14" /> Facebook
                        </a>
                        <a class="tombol tombol--kecil tombol--netral" target="_blank" rel="noopener"
                            href="https://wa.me/?text={{ urlencode($berita->judul.' — '.url()->current()) }}">
                            <x-ikon nama="whatsapp" ukuran="14" /> WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            @if($lainnya->isNotEmpty())
                <h2 class="judul-bagian">Berita Lainnya</h2>
                <div class="grid-kartu">
                    @foreach($lainnya as $item)
                        <article class="kartu-konten">
                            <a class="kartu-konten__gambar" href="{{ route('web.berita.show', $item->slug) }}">
                                <img src="{{ $item->file?->link_public_stream ?? asset($templateWeb.'/img/placeholder.svg') }}" alt="{{ $item->judul }}" loading="lazy">
                            </a>
                            <div class="kartu-konten__isi">
                                <h3 class="kartu-konten__judul"><a href="{{ route('web.berita.show', $item->slug) }}">{{ $item->judul }}</a></h3>
                                <p class="kartu-konten__teks">{{ tanggalIndo($item->tanggal, 'D MMMM Y') }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </article>

        <aside>
            <div class="kartu">
                <div class="kartu__kepala"><h2 class="kartu__judul"><x-ikon nama="bintang" ukuran="17" /> Paling Dibaca</h2></div>
                <div class="kartu__isi">
                    @foreach($populer as $item)
                        <div style="display:flex;gap:10px;padding:8px 0;border-top:1px dashed var(--garis);">
                            <img src="{{ $item->file?->link_public_stream ?? asset($templateWeb.'/img/placeholder.svg') }}"
                                alt="" loading="lazy" style="width:64px;height:48px;object-fit:cover;border-radius:6px;flex:none;">
                            <div>
                                <a href="{{ route('web.berita.show', $item->slug) }}" style="font-size:.88rem;font-weight:600;color:#10212f;">{{ $item->judul }}</a>
                                <div style="font-size:.76rem;color:var(--teks-lembut);">{{ tanggalIndo($item->tanggal, 'D MMM Y') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
