@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    <div class="tata-letak">
        <div>
            <div class="kartu">
                <div class="kartu__kepala">
                    <h2 class="kartu__judul"><x-ikon nama="kalender" ukuran="17" /> Daftar Agenda</h2>
                    <form method="get" action="{{ route('web.agenda') }}">
                        <select class="form-kontrol" name="jenis" onchange="this.form.submit()" style="padding:6px 10px;font-size:.85rem;">
                            <option value="">Semua jenis</option>
                            @foreach($daftarJenis as $kunci => $label)
                                <option value="{{ $kunci }}" @selected($jenis === $kunci)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="kartu__isi">
                    @forelse($agenda as $item)
                        <div style="display:flex;gap:14px;padding:12px 0;border-bottom:1px dashed var(--garis);">
                            <div class="agenda-tanggal" style="width:58px;">
                                <strong>{{ $item->tanggal_mulai->format('d') }}</strong>
                                <small>{{ $item->tanggal_mulai->locale('id')->isoFormat('MMM Y') }}</small>
                            </div>
                            <div style="flex:1;">
                                <h3 style="font-size:1.02rem;margin-bottom:3px;">{{ $item->judul }}</h3>
                                <div class="berita-meta" style="margin-bottom:6px;">
                                    <span class="lencana lencana--{{ $item->warna }}">{{ $item->nama_jenis }}</span>
                                    @if($item->tanggal_selesai && ! $item->tanggal_selesai->isSameDay($item->tanggal_mulai))
                                        <span><x-ikon nama="kalender" ukuran="13" /> s.d. {{ tanggalIndo($item->tanggal_selesai, 'D MMMM Y') }}</span>
                                    @endif
                                    @if($item->waktu)<span><x-ikon nama="jam" ukuran="13" /> {{ $item->waktu }}</span>@endif
                                    @if($item->lokasi)<span><x-ikon nama="lokasi" ukuran="13" /> {{ $item->lokasi }}</span>@endif
                                </div>
                                @if($item->deskripsi)
                                    <p style="margin:0;font-size:.9rem;color:var(--teks-lembut);">{{ potongTeks($item->deskripsi, 220) }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="kosong">
                            <strong>Belum ada agenda</strong>
                            Agenda kegiatan sekolah akan tampil di sini.
                        </div>
                    @endforelse
                </div>
            </div>

            {{ $agenda->links('vendor.pagination.sekolah') }}
        </div>

        <aside>
            @include('frontend.partials.kalender')
        </aside>
    </div>
</div>
@endsection
