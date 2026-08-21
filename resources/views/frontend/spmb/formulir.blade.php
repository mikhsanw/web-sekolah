@extends('frontend.layouts.app')

@section('konten')
@php
    $nilai = fn (string $kunci, $bawaan = null) => old($kunci, $pendaftar->{$kunci} ?? $bawaan);
    $nisnTerkunci = $pendaftar->nisn ?? ($calon['nisn'] ?? '');
    $lahirTerkunci = $pendaftar?->tanggal_lahir?->toDateString() ?? ($calon['tanggal_lahir'] ?? '');
@endphp
<div class="wadah">
    @include('frontend.partials.pesan')

    <div class="kartu">
        <div class="kartu__kepala">
            <h2 class="kartu__judul"><x-ikon nama="dokumen" ukuran="17" /> Data Calon Peserta Didik</h2>
            @if($periode)<span class="lencana lencana--primary">TA {{ $periode->tahun_ajaran }}</span>@endif
        </div>

        <form method="post" action="{{ route('web.spmb.simpan') }}">
            @csrf
            <div class="kartu__isi">
                <div class="form-grid">
                    <div class="form-baris">
                        <label class="form-label" for="nisn">NISN <span class="wajib">*</span></label>
                        <input class="form-kontrol" type="text" id="nisn" name="nisn" value="{{ $nisnTerkunci }}" readonly>
                        <div class="form-bantuan">NISN dikunci sesuai data saat masuk.</div>
                    </div>

                    <div class="form-baris">
                        <label class="form-label" for="nik">NIK <span class="wajib">*</span></label>
                        <input class="form-kontrol" type="text" id="nik" name="nik" inputmode="numeric" maxlength="16"
                            value="{{ $nilai('nik') }}" required>
                        @error('nik')<div class="form-galat">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-baris">
                        <label class="form-label" for="nama">Nama Lengkap <span class="wajib">*</span></label>
                        <input class="form-kontrol" type="text" id="nama" name="nama" value="{{ $nilai('nama') }}" required>
                        @error('nama')<div class="form-galat">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-baris">
                        <label class="form-label" for="asal_sekolah">Asal Sekolah <span class="wajib">*</span></label>
                        <input class="form-kontrol" type="text" id="asal_sekolah" name="asal_sekolah"
                            value="{{ $nilai('asal_sekolah') }}" required>
                        @error('asal_sekolah')<div class="form-galat">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-baris">
                        <label class="form-label" for="jenis_kelamin">Jenis Kelamin <span class="wajib">*</span></label>
                        <select class="form-kontrol" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">— pilih —</option>
                            @foreach(config('sekolah.enum.jenis_kelamin') as $kunci => $label)
                                <option value="{{ $kunci }}" @selected($nilai('jenis_kelamin') === $kunci)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('jenis_kelamin')<div class="form-galat">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-baris">
                        <label class="form-label" for="agama">Agama <span class="wajib">*</span></label>
                        <select class="form-kontrol" id="agama" name="agama" required>
                            <option value="">— pilih —</option>
                            @foreach(config('sekolah.enum.agama') as $kunci => $label)
                                <option value="{{ $kunci }}" @selected($nilai('agama') === $kunci)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('agama')<div class="form-galat">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-baris">
                        <label class="form-label" for="tempat_lahir">Tempat Lahir <span class="wajib">*</span></label>
                        <input class="form-kontrol" type="text" id="tempat_lahir" name="tempat_lahir"
                            value="{{ $nilai('tempat_lahir') }}" required>
                        @error('tempat_lahir')<div class="form-galat">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-baris">
                        <label class="form-label" for="tanggal_lahir">Tanggal Lahir <span class="wajib">*</span></label>
                        <input class="form-kontrol" type="date" id="tanggal_lahir" name="tanggal_lahir"
                            value="{{ $lahirTerkunci }}" readonly>
                    </div>

                    <div class="form-baris">
                        <label class="form-label" for="no_hp">Nomor HP <span class="wajib">*</span></label>
                        <input class="form-kontrol" type="text" id="no_hp" name="no_hp" inputmode="tel"
                            value="{{ $nilai('no_hp') }}" required>
                        @error('no_hp')<div class="form-galat">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-baris">
                        <label class="form-label" for="nama_orang_tua">Nama Orang Tua/Wali</label>
                        <input class="form-kontrol" type="text" id="nama_orang_tua" name="nama_orang_tua"
                            value="{{ $nilai('nama_orang_tua') }}">
                    </div>
                </div>

                <div class="form-baris">
                    <label class="form-label" for="alamat">Alamat Lengkap <span class="wajib">*</span></label>
                    <textarea class="form-kontrol" id="alamat" name="alamat" required>{{ $nilai('alamat') }}</textarea>
                    @error('alamat')<div class="form-galat">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="kartu__kaki" style="display:flex;gap:8px;flex-wrap:wrap;">
                <button class="tombol" type="submit">
                    <x-ikon nama="cek" ukuran="15" /> {{ $pendaftar ? 'Perbarui Data' : 'Kirim Pendaftaran' }}
                </button>
                <a class="tombol tombol--netral" href="{{ route('web.spmb.informasi') }}">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
