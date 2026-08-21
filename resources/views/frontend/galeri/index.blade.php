@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    <div class="kartu">
        <div class="kartu__kepala">
            <h2 class="kartu__judul"><x-ikon nama="gambar" ukuran="17" /> Dokumentasi Kegiatan</h2>
            <div style="display:flex;gap:6px;">
                <a class="tombol tombol--kecil {{ $kategori ? 'tombol--netral' : '' }}" href="{{ route('web.galeri.index') }}">Semua</a>
                @foreach($daftarKategori as $kunci => $label)
                    <a class="tombol tombol--kecil {{ $kategori === $kunci ? '' : 'tombol--netral' }}"
                        href="{{ route('web.galeri.index', ['kategori' => $kunci]) }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>
        <div class="kartu__isi">
            @if($galeri->isEmpty())
                <div class="kosong">
                    <strong>Belum ada dokumentasi</strong>
                    Galeri akan terisi setelah admin mengunggah foto atau video.
                </div>
            @else
                <div class="galeri-grid">
                    @foreach($galeri as $item)
                        <a class="galeri-item" href="{{ route('web.galeri.show', $item->slug) }}">
                            <img src="{{ $item->berkasUrl('gambar', asset($templateWeb.'/img/placeholder.svg')) }}"
                                alt="{{ $item->judul }}" loading="lazy">
                            <span class="galeri-item__label">{{ $item->judul }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{ $galeri->links('vendor.pagination.sekolah') }}
</div>
@endsection
