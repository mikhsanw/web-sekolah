<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Prestasi;
use Illuminate\Http\Request;

class PrestasiController extends FrontendController
{
    public function index(Request $request, string $kategori)
    {
        $judul = config('sekolah.enum.kategori_prestasi')[$kategori] ?? 'Prestasi';

        $prestasi = Prestasi::query()
            ->with('files')
            ->where('kategori', $kategori)
            ->orderByDesc('tahun')
            ->orderByDesc('tanggal')
            ->paginate(12)
            ->withQueryString();

        return $this->tampil('kesiswaan.prestasi', [
            'prestasi' => $prestasi,
            'kategori' => $kategori,
        ], $judul, [($kategori === 'guru' ? 'GTK' : 'Kesiswaan') => null, $judul => null]);
    }
}
