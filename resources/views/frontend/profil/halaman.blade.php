@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    <div class="kartu">
        @php $gambar = $halaman->berkasUrl('gambar'); @endphp
        @if($gambar)
            <img src="{{ $gambar }}" alt="{{ $halaman->judul }}" style="width:100%;max-height:400px;object-fit:cover;">
        @endif
        <div class="kartu__isi">
            @if($halaman->ringkasan)
                <p style="font-size:1.03rem;color:var(--teks-lembut);">{{ $halaman->ringkasan }}</p>
                <hr>
            @endif
            <div class="artikel">
                {!! $halaman->isi ?: '<p>Konten halaman ini belum diisi.</p>' !!}
            </div>
        </div>
    </div>
</div>
@endsection
