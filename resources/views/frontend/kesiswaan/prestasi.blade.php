@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    @if($prestasi->isEmpty())
        <div class="kartu"><div class="kosong">
            <strong>Belum ada data prestasi</strong>
            Data prestasi akan tampil setelah admin menambahkannya.
        </div></div>
    @else
        <div class="grid-kartu">
            @foreach($prestasi as $item)
                <article class="kartu-konten">
                    <div class="kartu-konten__gambar">
                        <img src="{{ $item->berkasUrl('gambar', asset($templateWeb.'/img/placeholder.svg')) }}"
                            alt="{{ $item->judul }}" loading="lazy">
                    </div>
                    <div class="kartu-konten__isi">
                        <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:7px;">
                            <span class="lencana lencana--primary">{{ $item->nama_tingkat }}</span>
                            @if($item->peringkat)<span class="lencana lencana--warning">{{ $item->peringkat }}</span>@endif
                        </div>
                        <h3 class="kartu-konten__judul">{{ $item->judul }}</h3>
                        <p class="kartu-konten__teks">
                            @if($item->nama_peraih)<strong>{{ $item->nama_peraih }}</strong><br>@endif
                            {{ potongTeks($item->deskripsi, 120) }}
                        </p>
                        <div style="font-size:.8rem;color:var(--teks-lembut);margin-top:8px;">
                            @if($item->penyelenggara){{ $item->penyelenggara }} @endif
                            @if($item->tahun) • {{ $item->tahun }} @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{ $prestasi->links('vendor.pagination.sekolah') }}
    @endif
</div>
@endsection
