@php
    /** Widget kalender bulanan dengan penanda hari beragenda. */
    $awal = \Carbon\Carbon::create($tahunKalender, $bulanKalender, 1)->startOfMonth();
    $akhir = (clone $awal)->endOfMonth();
    $mulaiGrid = (clone $awal)->startOfWeek(\Carbon\Carbon::MONDAY);
    $selesaiGrid = (clone $akhir)->endOfWeek(\Carbon\Carbon::SUNDAY);
    $hariBeragenda = $hariBeragenda ?? [];
    $sebelum = (clone $awal)->subMonth();
    $sesudah = (clone $awal)->addMonth();
@endphp
<div class="kartu">
    <div class="kartu__kepala">
        <h2 class="kartu__judul"><x-ikon nama="kalender" ukuran="17" /> Kalender</h2>
        <div class="kalender-nav">
            <a href="{{ request()->fullUrlWithQuery(['bulan' => $sebelum->month, 'tahun' => $sebelum->year]) }}#kalender"
                aria-label="Bulan sebelumnya">&lsaquo;</a>
            <a href="{{ request()->fullUrlWithQuery(['bulan' => $sesudah->month, 'tahun' => $sesudah->year]) }}#kalender"
                aria-label="Bulan berikutnya">&rsaquo;</a>
        </div>
    </div>
    <div class="kartu__isi" id="kalender">
        <p style="text-align:center;font-weight:700;margin-bottom:8px;color:var(--warna-utama);">
            {{ $awal->locale('id')->isoFormat('MMMM Y') }}
        </p>
        <table class="kalender">
            <thead>
                <tr>
                    <th>Sen</th><th>Sel</th><th>Rab</th><th>Kam</th><th>Jum</th><th>Sab</th><th>Min</th>
                </tr>
            </thead>
            <tbody>
                @php $kursor = clone $mulaiGrid; @endphp
                @while($kursor <= $selesaiGrid)
                    <tr>
                        @for($i = 0; $i < 7; $i++)
                            @php
                                $tanggal = $kursor->toDateString();
                                $kelas = ['kalender__hari'];
                                if ($kursor->month !== $awal->month) { $kelas[] = 'kalender__hari--luar'; }
                                if ($kursor->dayOfWeek === \Carbon\Carbon::SUNDAY) { $kelas[] = 'kalender__hari--minggu'; }
                                if ($kursor->isToday()) { $kelas[] = 'kalender__hari--ini'; }
                                if (in_array($tanggal, $hariBeragenda, true)) { $kelas[] = 'kalender__hari--acara'; }
                            @endphp
                            <td>
                                <span class="{{ implode(' ', $kelas) }}"
                                    @if(in_array($tanggal, $hariBeragenda, true)) title="Ada agenda pada tanggal ini" @endif>
                                    {{ $kursor->day }}
                                </span>
                            </td>
                            @php $kursor->addDay(); @endphp
                        @endfor
                    </tr>
                @endwhile
            </tbody>
        </table>

        <hr>

        <h3 style="font-size:.92rem;margin-bottom:8px;">Agenda Terdekat</h3>
        @if(($agendaKalender ?? collect())->isEmpty())
            <p style="font-size:.85rem;color:var(--teks-lembut);margin:0;">Belum ada agenda pada bulan ini.</p>
        @else
            <ul class="agenda-list">
                @foreach($agendaKalender as $agenda)
                    <li>
                        <div class="agenda-tanggal">
                            <strong>{{ $agenda->tanggal_mulai->format('d') }}</strong>
                            <small>{{ $agenda->tanggal_mulai->locale('id')->isoFormat('MMM') }}</small>
                        </div>
                        <div>
                            <div class="agenda-judul">{{ $agenda->judul }}</div>
                            <div class="agenda-info">
                                {{ $agenda->nama_jenis }}@if($agenda->lokasi) • {{ $agenda->lokasi }}@endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    <div class="kartu__kaki">
        <a class="tombol tombol--kecil tombol--garis tombol--blok" href="{{ route('web.agenda') }}">Lihat semua agenda</a>
    </div>
</div>
