<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeritaController extends FrontendController
{
    public function index(Request $request)
    {
        $kunci = trim((string) $request->query('q', ''));

        $berita = Berita::query()
            ->with('file')
            ->when($kunci !== '', function ($query) use ($kunci) {
                $query->where(function ($sub) use ($kunci) {
                    $sub->where('judul', 'like', '%'.$kunci.'%')
                        ->orWhere('isi', 'like', '%'.$kunci.'%');
                });
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(8)
            ->withQueryString();

        return $this->tampil('berita.index', [
            'berita' => $berita,
            'kunci' => $kunci,
            'populer' => $this->populer(),
        ], 'Berita Sekolah', ['Berita' => null]);
    }

    public function show(string $slug)
    {
        $berita = Berita::query()->with('file')->where('slug', $slug)->firstOrFail();

        // Hitung pembaca tanpa memicu timestamp/observer agar urutan berita tidak berubah.
        Berita::query()->whereKey($berita->getKey())->update(['view' => DB::raw('COALESCE(view, 0) + 1')]);

        $lainnya = Berita::query()
            ->with('file')
            ->whereKeyNot($berita->getKey())
            ->orderByDesc('tanggal')
            ->limit(4)
            ->get();

        return $this->tampil('berita.show', [
            'berita' => $berita,
            'lainnya' => $lainnya,
            'populer' => $this->populer(),
            'deskripsiHalaman' => potongTeks($berita->isi, 160),
        ], $berita->judul, ['Berita' => route('web.berita.index'), $berita->judul => null]);
    }

    protected function populer()
    {
        return Berita::query()
            ->with('file')
            ->orderByDesc('view')
            ->limit(5)
            ->get();
    }
}
