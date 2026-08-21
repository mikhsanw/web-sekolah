@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    @if($fasilitas->isEmpty())
        <div class="kartu"><div class="kosong">
            <strong>Belum ada data fasilitas</strong>
            Data sarana prasarana akan tampil setelah admin menambahkannya.
        </div></div>
    @else
        <div class="grid-kartu">
            @foreach($fasilitas as $item)
                <article class="kartu-konten">
                    <div class="kartu-konten__gambar">
                        <img src="{{ $item->berkasUrl('gambar', asset($templateWeb.'/img/placeholder.svg')) }}"
                            alt="{{ $item->nama }}" loading="lazy">
                    </div>
                    <div class="kartu-konten__isi">
                        <h3 class="kartu-konten__judul">{{ $item->nama }}</h3>
                        <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:7px;">
                            <span class="lencana lencana--primary">{{ $item->jumlah }} unit</span>
                            <span class="lencana lencana--{{ $item->kondisi === 'baik' ? 'success' : ($item->kondisi === 'rusak_berat' ? 'danger' : 'warning') }}">
                                {{ $item->nama_kondisi }}
                            </span>
                        </div>
                        <p class="kartu-konten__teks">{{ potongTeks($item->deskripsi, 140) }}</p>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="kartu" style="margin-top:22px;">
            <div class="kartu__kepala"><h2 class="kartu__judul"><x-ikon nama="gedung" ukuran="17" /> Rekapitulasi</h2></div>
            <div class="kartu__isi tabel-gulir">
                <table class="tabel">
                    <thead>
                        <tr><th style="width:40px;">No</th><th>Nama Sarana</th><th>Jumlah</th><th>Kondisi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($fasilitas as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>{{ $item->nama_kondisi }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
