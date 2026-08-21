<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Kelulusan;
use App\Models\KelulusanPeriode;
use Illuminate\Http\Request;

/**
 * Pengumuman kelulusan: siswa masuk memakai NISN + tanggal lahir.
 */
class KelulusanController extends FrontendController
{
    public function index()
    {
        $periode = KelulusanPeriode::query()
            ->withCount('kelulusan')
            ->where('aktif', true)
            ->orderByDesc('tahun_ajaran')
            ->get();

        return $this->tampil('kelulusan.index', [
            'periode' => $periode,
        ], 'Pengumuman Kelulusan', ['Kelulusan' => null]);
    }

    public function periode(string $periode)
    {
        $data = $this->cariPeriode($periode);

        if (session()->has($this->kunciSesi($data))) {
            return redirect()->route('web.kelulusan.hasil', $data->slug);
        }

        return $this->tampil('kelulusan.masuk', [
            'periode' => $data,
        ], 'Kelulusan Angkatan '.$data->tahun_ajaran, [
            'Kelulusan' => route('web.kelulusan.index'),
            'Angkatan '.$data->tahun_ajaran => null,
        ]);
    }

    public function cek(Request $request, string $periode)
    {
        $data = $this->cariPeriode($periode);

        $kredensial = $request->validate([
            'nisn' => ['required', 'string', 'max:20'],
            'tanggal_lahir' => ['required', 'date'],
        ], [], ['nisn' => 'NISN', 'tanggal_lahir' => 'tanggal lahir']);

        if (! $data->sudah_diumumkan) {
            return back()->with('galat', 'Pengumuman kelulusan angkatan ini belum dibuka.');
        }

        $siswa = Kelulusan::query()
            ->where('kelulusan_periode_id', $data->id)
            ->where('nisn', trim($kredensial['nisn']))
            ->whereDate('tanggal_lahir', $kredensial['tanggal_lahir'])
            ->first();

        if (! $siswa) {
            return back()
                ->withInput($request->only('nisn'))
                ->with('galat', 'Data tidak ditemukan. Pastikan NISN dan tanggal lahir sesuai dengan data sekolah.');
        }

        session()->put($this->kunciSesi($data), $siswa->id);

        return redirect()->route('web.kelulusan.hasil', $data->slug);
    }

    public function hasil(string $periode)
    {
        $data = $this->cariPeriode($periode);
        $idSiswa = session($this->kunciSesi($data));

        if (! $idSiswa) {
            return redirect()->route('web.kelulusan.periode', $data->slug);
        }

        $siswa = Kelulusan::query()->with('periode')->find($idSiswa);

        if (! $siswa || ! $data->sudah_diumumkan) {
            session()->forget($this->kunciSesi($data));

            return redirect()->route('web.kelulusan.periode', $data->slug)
                ->with('galat', 'Sesi pengumuman berakhir. Silakan masuk kembali.');
        }

        return $this->tampil('kelulusan.hasil', [
            'periode' => $data,
            'siswa' => $siswa,
        ], 'Hasil Kelulusan', [
            'Kelulusan' => route('web.kelulusan.index'),
            'Angkatan '.$data->tahun_ajaran => null,
        ]);
    }

    public function keluar(string $periode)
    {
        $data = $this->cariPeriode($periode);
        session()->forget($this->kunciSesi($data));

        return redirect()->route('web.kelulusan.periode', $data->slug)
            ->with('sukses', 'Anda telah keluar dari halaman pengumuman.');
    }

    protected function cariPeriode(string $slug): KelulusanPeriode
    {
        return KelulusanPeriode::query()
            ->where('slug', $slug)
            ->where('aktif', true)
            ->firstOrFail();
    }

    protected function kunciSesi(KelulusanPeriode $periode): string
    {
        return 'kelulusan.'.$periode->id;
    }
}
