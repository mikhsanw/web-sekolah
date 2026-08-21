<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Berita;
use App\Models\Ekstrakurikuler;
use App\Models\Galeri;
use App\Models\KelulusanPeriode;
use App\Models\Pegawai;
use App\Models\Prestasi;
use App\Models\SpmbPeriode;
use App\Services\KalenderSekolah;
use Illuminate\Http\Request;

class BerandaController extends FrontendController
{
    public function index(Request $request)
    {
        $beritaTerbaru = Berita::query()
            ->with('file')
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->limit((int) config('sekolah.tampilan.berita_beranda', 4))
            ->get();

        $galeri = Galeri::query()
            ->with('files')
            ->where('aktif', true)
            ->orderByDesc('tanggal')
            ->orderBy('urutan')
            ->limit((int) config('sekolah.tampilan.galeri_beranda', 6))
            ->get();

        $data = array_merge(
            KalenderSekolah::data($request, (int) config('sekolah.tampilan.agenda_beranda', 5)),
            [
                'beritaTerbaru' => $beritaTerbaru,
                'galeri' => $galeri,
                'prestasiTerbaru' => Prestasi::query()->orderByDesc('tahun')->orderByDesc('created_at')->limit(4)->get(),
                'spmbAktif' => SpmbPeriode::query()->where('aktif', true)->orderByDesc('tahun_ajaran')->first(),
                'kelulusanAktif' => KelulusanPeriode::query()->where('aktif', true)->where('dibuka', true)->orderByDesc('tahun_ajaran')->first(),
                'statistik' => [
                    'guru' => Pegawai::query()->where('aktif', true)->where('jenis', 'guru')->count(),
                    'tendik' => Pegawai::query()->where('aktif', true)->where('jenis', 'tendik')->count(),
                    'prestasi' => Prestasi::query()->count(),
                    'ekstrakurikuler' => Ekstrakurikuler::query()->where('aktif', true)->count(),
                ],
            ]
        );

        return $this->tampil('beranda.index', $data);
    }
}
