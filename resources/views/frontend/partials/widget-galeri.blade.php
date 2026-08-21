<div class="kartu">
    <div class="kartu__kepala">
        <h2 class="kartu__judul"><x-ikon nama="gambar" ukuran="17" /> Galeri</h2>
        <a class="tombol tombol--kecil tombol--netral" href="{{ route('web.galeri.index') }}">Semua</a>
    </div>
    <div class="kartu__isi">
        @if($galeri->isEmpty())
            <p style="font-size:.85rem;color:var(--teks-lembut);margin:0;">Belum ada foto pada galeri.</p>
        @else
            <div class="galeri-grid galeri-grid--samping">
                @foreach($galeri as $item)
                    <a class="galeri-item" href="{{ route('web.galeri.show', $item->slug) }}" title="{{ $item->judul }}">
                        <img src="{{ $item->berkasUrl('gambar', asset(config('master.app.web.template').'/img/placeholder.svg')) }}"
                            alt="{{ $item->judul }}" loading="lazy">
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
