<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Galeri;
use Illuminate\Http\Request;

class GaleriController extends FrontendController
{
    public function index(Request $request)
    {
        $kategori = $request->query('kategori');
        $daftarKategori = config('sekolah.enum.kategori_galeri');

        $galeri = Galeri::query()
            ->with('files')
            ->where('aktif', true)
            ->when($kategori && array_key_exists($kategori, $daftarKategori), fn ($query) => $query->where('kategori', $kategori))
            ->orderByDesc('tanggal')
            ->orderBy('urutan')
            ->paginate(18)
            ->withQueryString();

        return $this->tampil('galeri.index', [
            'galeri' => $galeri,
            'kategori' => $kategori,
            'daftarKategori' => $daftarKategori,
        ], 'Galeri Sekolah', ['Galeri' => null]);
    }

    public function show(string $slug)
    {
        $galeri = Galeri::query()->with('files')->where('slug', $slug)->where('aktif', true)->firstOrFail();

        return $this->tampil('galeri.show', [
            'galeri' => $galeri,
            'lainnya' => Galeri::query()
                ->with('files')
                ->where('aktif', true)
                ->whereKeyNot($galeri->getKey())
                ->orderByDesc('tanggal')
                ->limit(8)
                ->get(),
        ], $galeri->judul, ['Galeri' => route('web.galeri.index'), $galeri->judul => null]);
    }
}
