@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    @include('frontend.partials.pesan')

    @if($spmbAktif?->sedang_dibuka || $kelulusanAktif?->sudah_diumumkan)
        <div class="kartu" style="border-left:4px solid var(--warna-aksen);">
            <div class="kartu__isi" style="display:flex;flex-wrap:wrap;gap:14px;align-items:center;justify-content:space-between;">
                <div>
                    <h2 style="margin:0 0 3px;font-size:1.05rem;">Pengumuman</h2>
                    <p style="margin:0;font-size:.92rem;color:var(--teks-lembut);">
                        @if($spmbAktif?->sedang_dibuka)
                            Pendaftaran SPMB Tahun Ajaran {{ $spmbAktif->tahun_ajaran }} sedang dibuka.
                        @endif
                        @if($kelulusanAktif?->sudah_diumumkan)
                            Pengumuman kelulusan angkatan {{ $kelulusanAktif->tahun_ajaran }} sudah dapat diakses.
                        @endif
                    </p>
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @if($spmbAktif?->sedang_dibuka)
                        <a class="tombol tombol--aksen" href="{{ route('web.spmb.pendaftaran') }}">Daftar SPMB</a>
                    @endif
                    @if($kelulusanAktif?->sudah_diumumkan)
                        <a class="tombol" href="{{ route('web.kelulusan.periode', $kelulusanAktif->slug) }}">Cek Kelulusan</a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="tata-letak">
        <div>
            <h2 class="judul-bagian"><x-ikon nama="daftar" ukuran="20" /> Berita Terbaru</h2>

            <div class="kartu">
                @forelse($beritaTerbaru as $berita)
                    @include('frontend.partials.kartu-berita', ['berita' => $berita])
                @empty
                    <div class="kosong">
                        <strong>Belum ada berita</strong>
                        Berita sekolah akan tampil di sini setelah admin menambahkannya.
                    </div>
                @endforelse

                @if($beritaTerbaru->isNotEmpty())
                    <div class="kartu__kaki" style="text-align:center;">
                        <a class="tombol tombol--garis" href="{{ route('web.berita.index') }}">
                            Lihat semua berita <x-ikon nama="panah-kanan" ukuran="14" />
                        </a>
                    </div>
                @endif
            </div>

            @if($prestasiTerbaru->isNotEmpty())
                <h2 class="judul-bagian"><x-ikon nama="trofi" ukuran="20" /> Prestasi Terbaru</h2>
                <div class="grid-kartu">
                    @foreach($prestasiTerbaru as $item)
                        <article class="kartu-konten">
                            <div class="kartu-konten__gambar">
                                <img src="{{ $item->berkasUrl('gambar', asset($templateWeb.'/img/placeholder.svg')) }}"
                                    alt="{{ $item->judul }}" loading="lazy">
                            </div>
                            <div class="kartu-konten__isi">
                                <span class="lencana lencana--primary">{{ $item->nama_tingkat }}</span>
                                <h3 class="kartu-konten__judul" style="margin-top:7px;">{{ $item->judul }}</h3>
                                <p class="kartu-konten__teks">{{ $item->nama_peraih }} @if($item->tahun) • {{ $item->tahun }} @endif</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif

            <div class="statistik" style="margin-top:22px;">
                <div class="statistik__item">
                    <div class="statistik__angka">{{ $statistik['guru'] }}</div>
                    <div class="statistik__label">Guru</div>
                </div>
                <div class="statistik__item">
                    <div class="statistik__angka">{{ $statistik['tendik'] }}</div>
                    <div class="statistik__label">Tenaga Kependidikan</div>
                </div>
                <div class="statistik__item">
                    <div class="statistik__angka">{{ $statistik['ekstrakurikuler'] }}</div>
                    <div class="statistik__label">Ekstrakurikuler</div>
                </div>
                <div class="statistik__item">
                    <div class="statistik__angka">{{ $statistik['prestasi'] }}</div>
                    <div class="statistik__label">Prestasi</div>
                </div>
            </div>
        </div>

        <aside>
            @include('frontend.partials.kalender')
            @include('frontend.partials.widget-galeri')
        </aside>
    </div>
</div>
@endsection
