<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Agenda;
use App\Models\Ekstrakurikuler;
use App\Services\KalenderSekolah;
use Illuminate\Http\Request;

class ProgramController extends FrontendController
{
    public function agenda(Request $request)
    {
        $jenis = $request->query('jenis');
        $daftarJenis = config('sekolah.enum.jenis_agenda');

        $agenda = Agenda::query()
            ->where('aktif', true)
            ->when($jenis && array_key_exists($jenis, $daftarJenis), fn ($query) => $query->where('jenis', $jenis))
            ->orderByDesc('tanggal_mulai')
            ->paginate(12)
            ->withQueryString();

        return $this->tampil('program.agenda', array_merge(
            KalenderSekolah::data($request),
            [
                'agenda' => $agenda,
                'jenis' => $jenis,
                'daftarJenis' => $daftarJenis,
            ]
        ), 'Agenda Kegiatan', ['Program Sekolah' => null, 'Agenda' => null]);
    }

    public function ekstrakurikuler()
    {
        return $this->tampil('program.ekstrakurikuler', [
            'ekstrakurikuler' => Ekstrakurikuler::query()
                ->with('files')
                ->where('aktif', true)
                ->orderBy('urutan')
                ->orderBy('nama')
                ->get(),
        ], 'Ekstrakurikuler', ['Program Sekolah' => null, 'Ekstrakurikuler' => null]);
    }
}
