@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    <div class="kotak-akses">
        @include('frontend.partials.pesan')

        <div class="kartu">
            <div class="kartu__kepala">
                <h2 class="kartu__judul"><x-ikon nama="kunci" ukuran="17" /> Masuk / Daftar</h2>
            </div>
            <div class="kartu__isi">
                @if(! $periode)
                    <div class="alert alert--peringatan" style="margin:0;">Belum ada periode SPMB yang dibuka.</div>
                @else
                    <p style="font-size:.92rem;color:var(--teks-lembut);">
                        {{ $periode->judul }} — Tahun Ajaran {{ $periode->tahun_ajaran }}
                    </p>

                    @unless($periode->sedang_dibuka)
                        <div class="alert alert--peringatan">
                            Pendaftaran SPMB sedang tidak dibuka.
                            @if($periode->tanggal_buka)
                                Dibuka mulai {{ tanggalIndo($periode->tanggal_buka, 'D MMMM Y HH:mm') }} WIB.
                            @endif
                        </div>
                    @endunless

                    <form method="post" action="{{ route('web.spmb.masuk') }}">
                        @csrf
                        <div class="form-baris">
                            <label class="form-label" for="nisn">NISN <span class="wajib">*</span></label>
                            <input class="form-kontrol" type="text" id="nisn" name="nisn" inputmode="numeric"
                                value="{{ old('nisn') }}" required autocomplete="off" @disabled(! $periode->sedang_dibuka)>
                            <div class="form-bantuan">Belum pernah mendaftar? Isi NISN dan tanggal lahir untuk membuat pendaftaran baru.</div>
                        </div>

                        <div class="form-baris">
                            <label class="form-label" for="tanggal_lahir">Tanggal Lahir <span class="wajib">*</span></label>
                            <input class="form-kontrol" type="date" id="tanggal_lahir" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir') }}" required @disabled(! $periode->sedang_dibuka)>
                        </div>

                        <button class="tombol tombol--blok" type="submit" @disabled(! $periode->sedang_dibuka)>
                            Lanjutkan
                        </button>
                    </form>

                    <hr>
                    <a class="tombol tombol--blok tombol--garis" href="{{ route('web.spmb.informasi') }}">
                        <x-ikon nama="info" ukuran="15" /> Baca Informasi SPMB
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
