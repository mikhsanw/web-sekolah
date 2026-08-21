@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    <div class="tata-letak">
        <div>
            @if($kunci !== '')
                <p style="color:var(--teks-lembut);">Hasil pencarian berita untuk <strong>{{ $kunci }}</strong> — {{ $berita->total() }} berita ditemukan.</p>
            @endif

            <div class="kartu">
                @forelse($berita as $item)
                    @include('frontend.partials.kartu-berita', ['berita' => $item])
                @empty
                    <div class="kosong">
                        <strong>Belum ada berita</strong>
                        Silakan kembali lagi nanti.
                    </div>
                @endforelse
            </div>

            {{ $berita->links('vendor.pagination.sekolah') }}
        </div>

        <aside>
            <div class="kartu">
                <div class="kartu__kepala"><h2 class="kartu__judul"><x-ikon nama="cari" ukuran="17" /> Cari Berita</h2></div>
                <div class="kartu__isi">
                    <form action="{{ route('web.berita.index') }}" method="get">
                        <div class="form-baris">
                            <input class="form-kontrol" type="search" name="q" value="{{ $kunci }}" placeholder="Kata kunci…">
                        </div>
                        <button class="tombol tombol--blok" type="submit">Cari</button>
                    </form>
                </div>
            </div>

            <div class="kartu">
                <div class="kartu__kepala"><h2 class="kartu__judul"><x-ikon nama="bintang" ukuran="17" /> Paling Dibaca</h2></div>
                <div class="kartu__isi">
                    @forelse($populer as $item)
                        <div style="display:flex;gap:10px;padding:8px 0;border-top:1px dashed var(--garis);">
                            <img src="{{ $item->file?->link_public_stream ?? asset($templateWeb.'/img/placeholder.svg') }}"
                                alt="" loading="lazy" style="width:64px;height:48px;object-fit:cover;border-radius:6px;flex:none;">
                            <div>
                                <a href="{{ route('web.berita.show', $item->slug) }}" style="font-size:.88rem;font-weight:600;color:#10212f;">{{ $item->judul }}</a>
                                <div style="font-size:.76rem;color:var(--teks-lembut);">{{ tanggalIndo($item->tanggal, 'D MMM Y') }}</div>
                            </div>
                        </div>
                    @empty
                        <p style="font-size:.85rem;color:var(--teks-lembut);margin:0;">Belum ada data.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
