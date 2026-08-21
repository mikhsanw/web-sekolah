@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    @if($pegawai->isEmpty())
        <div class="kartu"><div class="kosong">
            <strong>Belum ada data</strong>
            Data {{ $jenis === 'guru' ? 'guru' : 'tenaga kependidikan' }} belum diisi admin.
        </div></div>
    @else
        <div class="grid-kartu">
            @foreach($pegawai as $orang)
                <div class="kartu-konten kartu-orang">
                    <img class="kartu-orang__foto"
                        src="{{ $orang->berkasUrl('foto', asset($templateWeb.'/img/avatar.svg')) }}"
                        alt="{{ $orang->nama }}" loading="lazy">
                    <div class="kartu-orang__nama">{{ $orang->nama }}</div>
                    <div class="kartu-orang__jabatan">{{ $orang->jabatan ?: $orang->nama_jenis }}</div>
                    @if($orang->mata_pelajaran)
                        <div style="margin-top:6px;"><span class="lencana lencana--primary">{{ $orang->mata_pelajaran }}</span></div>
                    @endif
                    @if($orang->pendidikan_terakhir)
                        <div style="font-size:.8rem;color:var(--teks-lembut);margin-top:6px;">{{ $orang->pendidikan_terakhir }}</div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="kartu" style="margin-top:22px;">
            <div class="kartu__kepala"><h2 class="kartu__judul"><x-ikon nama="daftar" ukuran="17" /> Daftar Lengkap</h2></div>
            <div class="kartu__isi tabel-gulir">
                <table class="tabel">
                    <thead>
                        <tr>
                            <th style="width:40px;">No</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                            @if($jenis === 'guru')<th>Mata Pelajaran</th>@endif
                            <th>L/P</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pegawai as $i => $orang)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $orang->nama }}</td>
                                <td>{{ $orang->nip ?: '-' }}</td>
                                <td>{{ $orang->jabatan ?: '-' }}</td>
                                @if($jenis === 'guru')<td>{{ $orang->mata_pelajaran ?: '-' }}</td>@endif
                                <td>{{ $orang->jenis_kelamin ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
