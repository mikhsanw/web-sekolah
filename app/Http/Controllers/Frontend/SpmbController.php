<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\SpmbPendaftaranRequest;
use App\Models\Halaman;
use App\Models\SpmbPendaftar;
use App\Models\SpmbPeriode;
use Illuminate\Http\Request;

/**
 * SPMB online: calon peserta didik masuk dengan NISN + tanggal lahir,
 * lalu mengisi/melihat formulir pendaftarannya.
 */
class SpmbController extends FrontendController
{
    protected const SESI_PENDAFTAR = 'spmb.pendaftar_id';

    protected const SESI_CALON = 'spmb.calon';

    public function informasi()
    {
        $periode = $this->periodeAktif();

        return $this->tampil('spmb.informasi', [
            'periode' => $periode,
            'halaman' => Halaman::query()->where('slug', 'informasi-spmb')->where('aktif', true)->first(),
        ], 'Informasi SPMB', ['SPMB' => null, 'Informasi' => null]);
    }

    public function pendaftaran()
    {
        $periode = $this->periodeAktif();

        if ($this->pendaftarSesi()) {
            return redirect()->route('web.spmb.bukti');
        }

        return $this->tampil('spmb.masuk', [
            'periode' => $periode,
        ], 'Pendaftaran SPMB', ['SPMB' => null, 'Pendaftaran' => null]);
    }

    public function masuk(Request $request)
    {
        $periode = $this->periodeAktif();

        if (! $periode || ! $periode->sedang_dibuka) {
            return back()->with('galat', 'Pendaftaran SPMB sedang tidak dibuka.');
        }

        $kredensial = $request->validate([
            'nisn' => ['required', 'string', 'digits_between:8,12'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
        ], [], ['nisn' => 'NISN', 'tanggal_lahir' => 'tanggal lahir']);

        $pendaftar = SpmbPendaftar::query()
            ->where('spmb_periode_id', $periode->id)
            ->where('nisn', $kredensial['nisn'])
            ->first();

        // NISN sudah terdaftar: tanggal lahir dipakai sebagai kunci akses.
        if ($pendaftar) {
            if (! $pendaftar->tanggal_lahir?->isSameDay($kredensial['tanggal_lahir'])) {
                return back()
                    ->withInput($request->only('nisn'))
                    ->with('galat', 'NISN sudah terdaftar, tetapi tanggal lahir tidak cocok. Hubungi panitia bila perlu bantuan.');
            }

            session()->put(self::SESI_PENDAFTAR, $pendaftar->id);
            session()->forget(self::SESI_CALON);

            return redirect()->route('web.spmb.bukti')
                ->with('sukses', 'Selamat datang kembali, '.$pendaftar->nama.'.');
        }

        // Pendaftar baru: simpan identitas awal lalu lanjut ke formulir.
        session()->put(self::SESI_CALON, [
            'periode_id' => $periode->id,
            'nisn' => $kredensial['nisn'],
            'tanggal_lahir' => $kredensial['tanggal_lahir'],
        ]);

        return redirect()->route('web.spmb.formulir');
    }

    public function formulir()
    {
        $periode = $this->periodeAktif();
        $pendaftar = $this->pendaftarSesi();
        $calon = session(self::SESI_CALON);

        if (! $pendaftar && ! $calon) {
            return redirect()->route('web.spmb.pendaftaran')
                ->with('galat', 'Silakan masuk dahulu memakai NISN dan tanggal lahir.');
        }

        if ($pendaftar && $pendaftar->status !== 'baru') {
            return redirect()->route('web.spmb.bukti')
                ->with('galat', 'Data Anda sudah diverifikasi panitia sehingga tidak dapat diubah.');
        }

        return $this->tampil('spmb.formulir', [
            'periode' => $periode,
            'pendaftar' => $pendaftar,
            'calon' => $calon,
        ], 'Formulir Pendaftaran SPMB', ['SPMB' => null, 'Formulir' => null]);
    }

    public function simpan(SpmbPendaftaranRequest $request)
    {
        $periode = $this->periodeAktif();
        $pendaftar = $this->pendaftarSesi();
        $calon = session(self::SESI_CALON);

        if (! $pendaftar && ! $calon) {
            return redirect()->route('web.spmb.pendaftaran')
                ->with('galat', 'Sesi pendaftaran berakhir. Silakan masuk kembali.');
        }

        if (! $periode || ! $periode->sedang_dibuka) {
            return back()->withInput()->with('galat', 'Pendaftaran SPMB sedang tidak dibuka.');
        }

        if ($pendaftar && $pendaftar->status !== 'baru') {
            return redirect()->route('web.spmb.bukti')
                ->with('galat', 'Data Anda sudah diverifikasi panitia sehingga tidak dapat diubah.');
        }

        $isi = $request->validated();

        // NISN dan tanggal lahir dikunci dari sesi agar tidak bisa ditukar lewat form.
        $isi['nisn'] = $pendaftar->nisn ?? $calon['nisn'];
        $isi['tanggal_lahir'] = $pendaftar?->tanggal_lahir?->toDateString() ?? $calon['tanggal_lahir'];

        $bentrok = SpmbPendaftar::query()
            ->where('spmb_periode_id', $periode->id)
            ->where('nisn', $isi['nisn'])
            ->when($pendaftar, fn ($query) => $query->whereKeyNot($pendaftar->getKey()))
            ->exists();

        if ($bentrok) {
            return back()->withInput()->with('galat', 'NISN tersebut sudah terdaftar pada periode ini.');
        }

        if ($pendaftar) {
            $pendaftar->update($isi);
            $pesan = 'Data pendaftaran berhasil diperbarui.';
        } else {
            $pendaftar = SpmbPendaftar::create(array_merge($isi, [
                'spmb_periode_id' => $periode->id,
                'no_pendaftaran' => SpmbPendaftar::buatNomorPendaftaran($periode),
                'status' => 'baru',
                'terdaftar_pada' => now(),
            ]));

            $pesan = 'Pendaftaran berhasil. Simpan nomor pendaftaran Anda.';
        }

        session()->put(self::SESI_PENDAFTAR, $pendaftar->id);
        session()->forget(self::SESI_CALON);

        return redirect()->route('web.spmb.bukti')->with('sukses', $pesan);
    }

    public function bukti()
    {
        $pendaftar = $this->pendaftarSesi();

        if (! $pendaftar) {
            return redirect()->route('web.spmb.pendaftaran')
                ->with('galat', 'Silakan masuk dahulu memakai NISN dan tanggal lahir.');
        }

        return $this->tampil('spmb.bukti', [
            'pendaftar' => $pendaftar->loadMissing('periode'),
        ], 'Bukti Pendaftaran SPMB', ['SPMB' => null, 'Bukti Pendaftaran' => null]);
    }

    public function keluar()
    {
        session()->forget([self::SESI_PENDAFTAR, self::SESI_CALON]);

        return redirect()->route('web.spmb.pendaftaran')
            ->with('sukses', 'Anda telah keluar dari halaman pendaftaran.');
    }

    protected function periodeAktif(): ?SpmbPeriode
    {
        return SpmbPeriode::query()
            ->where('aktif', true)
            ->orderByDesc('tahun_ajaran')
            ->first();
    }

    protected function pendaftarSesi(): ?SpmbPendaftar
    {
        $id = session(self::SESI_PENDAFTAR);

        return $id ? SpmbPendaftar::query()->with('periode')->find($id) : null;
    }
}
