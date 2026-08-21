@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    <div class="kartu">
        <div class="kartu__isi">
            <form action="{{ route('web.pencarian') }}" method="get" class="pencarian-form" style="margin-bottom:16px;">
                <input class="form-kontrol" type="search" name="q" value="{{ $kunci }}" placeholder="Ketik minimal 3 huruf…">
                <button class="tombol" type="submit">Cari</button>
            </form>

            @if($kunci === '')
                <p style="color:var(--teks-lembut);margin:0;">Masukkan kata kunci untuk mencari berita, halaman profil, agenda, dan prestasi.</p>
            @elseif(mb_strlen($kunci) < 3)
                <div class="alert alert--peringatan" style="margin:0;">Kata kunci minimal 3 huruf.</div>
            @elseif($hasil->isEmpty())
                <div class="kosong">
                    <strong>Tidak ada hasil untuk "{{ $kunci }}"</strong>
                    Coba gunakan kata kunci lain.
                </div>
            @else
                <p style="color:var(--teks-lembut);">{{ $hasil->count() }} hasil ditemukan untuk <strong>{{ $kunci }}</strong>.</p>
                @foreach($hasil as $item)
                    <div style="padding:12px 0;border-top:1px dashed var(--garis);">
                        <span class="lencana lencana--primary">{{ $item['jenis'] }}</span>
                        <h3 style="font-size:1.02rem;margin:6px 0 4px;">
                            <a href="{{ $item['url'] }}">{{ $item['judul'] }}</a>
                        </h3>
                        <p style="margin:0;font-size:.9rem;color:var(--teks-lembut);">{{ $item['ringkas'] }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
