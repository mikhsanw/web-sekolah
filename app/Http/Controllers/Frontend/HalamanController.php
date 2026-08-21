<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Halaman;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

class HalamanController extends FrontendController
{
    public function show(string $halaman)
    {
        $data = Halaman::query()->with('files')->where('slug', $halaman)->where('aktif', true)->firstOrFail();

        Halaman::query()->whereKey($data->getKey())->update(['view' => DB::raw('COALESCE(view, 0) + 1')]);

        return $this->tampil('profil.halaman', [
            'halaman' => $data,
            'deskripsiHalaman' => $data->ringkasan ?: potongTeks($data->isi, 160),
        ], $data->judul, [$data->nama_kategori => null, $data->judul => null]);
    }

    public function struktur()
    {
        return $this->tampil('profil.struktur', [
            'gambar' => pengaturanBerkas('struktur_organisasi'),
            'halaman' => Halaman::query()->where('slug', 'struktur-organisasi')->where('aktif', true)->first(),
            'pimpinan' => Pegawai::query()
                ->where('aktif', true)
                ->whereNotNull('jabatan')
                ->where('jabatan', '!=', '')
                ->orderBy('urutan')
                ->limit(12)
                ->get(),
        ], 'Struktur Organisasi', ['Profil' => null, 'Struktur Organisasi' => null]);
    }

    public function hymne()
    {
        return $this->tampil('profil.hymne', [
            'audio' => pengaturanBerkas('hymne'),
            'halaman' => Halaman::query()->where('slug', 'hymne-sekolah')->where('aktif', true)->first(),
        ], 'Hymne Sekolah', ['Profil' => null, 'Hymne Sekolah' => null]);
    }
}
