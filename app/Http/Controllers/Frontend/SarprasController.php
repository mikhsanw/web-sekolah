<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Fasilitas;
use App\Models\Halaman;

class SarprasController extends FrontendController
{
    public function peta()
    {
        return $this->tampil('sarpras.peta', [
            'gambar' => pengaturanBerkas('peta_sekolah'),
            'mapsEmbed' => pengaturan('maps_embed'),
            'halaman' => Halaman::query()->where('slug', 'peta-sekolah')->where('aktif', true)->first(),
        ], 'Peta Sekolah', ['Sarana Prasarana' => null, 'Peta Sekolah' => null]);
    }

    public function fasilitas()
    {
        return $this->tampil('sarpras.fasilitas', [
            'fasilitas' => Fasilitas::query()
                ->with('files')
                ->where('aktif', true)
                ->orderBy('urutan')
                ->orderBy('nama')
                ->get(),
        ], 'Fasilitas Sekolah', ['Sarana Prasarana' => null, 'Fasilitas' => null]);
    }
}
