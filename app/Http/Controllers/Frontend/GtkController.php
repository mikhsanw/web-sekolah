<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Pegawai;

class GtkController extends FrontendController
{
    public function guru()
    {
        return $this->daftar('guru', 'Informasi Guru');
    }

    public function tendik()
    {
        return $this->daftar('tendik', 'Informasi Tenaga Kependidikan');
    }

    protected function daftar(string $jenis, string $judul)
    {
        $pegawai = Pegawai::query()
            ->with('files')
            ->where('aktif', true)
            ->where('jenis', $jenis)
            ->orderBy('urutan')
            ->orderBy('nama')
            ->get();

        return $this->tampil('gtk.index', [
            'pegawai' => $pegawai,
            'jenis' => $jenis,
        ], $judul, ['GTK' => null, $judul => null]);
    }
}
