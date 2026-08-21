@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    <div class="kotak-akses">
        @include('frontend.partials.pesan')

        <div class="kartu">
            <div class="kartu__kepala">
                <h2 class="kartu__judul"><x-ikon nama="kunci" ukuran="17" /> Masuk Pengumuman</h2>
            </div>
            <div class="kartu__isi">
                <p style="font-size:.92rem;color:var(--teks-lembut);">
                    {{ $periode->judul }} — Angkatan {{ $periode->tahun_ajaran }}
                </p>

                @if(! $periode->sudah_diumumkan)
                    <div class="alert alert--peringatan">
                        Pengumuman belum dibuka.
                        @if($periode->tanggal_pengumuman)
                            Jadwal pengumuman: <strong>{{ tanggalIndo($periode->tanggal_pengumuman, 'dddd, D MMMM Y [pukul] HH:mm') }} WIB</strong>.
                        @endif
                    </div>
                @endif

                <form method="post" action="{{ route('web.kelulusan.cek', $periode->slug) }}">
                    @csrf
                    <div class="form-baris">
                        <label class="form-label" for="nisn">NISN <span class="wajib">*</span></label>
                        <input class="form-kontrol" type="text" id="nisn" name="nisn" inputmode="numeric"
                            value="{{ old('nisn') }}" required autocomplete="off"
                            @disabled(! $periode->sudah_diumumkan)>
                        <div class="form-bantuan">Nomor Induk Siswa Nasional, 10 digit.</div>
                    </div>

                    <div class="form-baris">
                        <label class="form-label" for="tanggal_lahir">Tanggal Lahir <span class="wajib">*</span></label>
                        <input class="form-kontrol" type="date" id="tanggal_lahir" name="tanggal_lahir" required
                            @disabled(! $periode->sudah_diumumkan)>
                    </div>

                    <button class="tombol tombol--blok" type="submit" @disabled(! $periode->sudah_diumumkan)>
                        Lihat Hasil Kelulusan
                    </button>
                </form>

                @if($periode->keterangan)
                    <hr>
                    <div style="font-size:.88rem;color:var(--teks-lembut);">{!! nl2br(e($periode->keterangan)) !!}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
