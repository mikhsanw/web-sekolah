<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Agenda;
use App\Models\Berita;
use App\Models\Halaman;
use App\Models\Prestasi;
use Illuminate\Http\Request;

class PencarianController extends FrontendController
{
    public function index(Request $request)
    {
        $kunci = trim((string) $request->query('q', ''));
        $hasil = collect();

        if (mb_strlen($kunci) >= 3) {
            $suka = '%'.$kunci.'%';

            $hasil = collect()
                ->merge(
                    Berita::query()
                        ->where(fn ($q) => $q->where('judul', 'like', $suka)->orWhere('isi', 'like', $suka))
                        ->orderByDesc('tanggal')->limit(15)->get()
                        ->map(fn ($item) => [
                            'jenis' => 'Berita',
                            'judul' => $item->judul,
                            'ringkas' => potongTeks($item->isi, 150),
                            'url' => route('web.berita.show', $item->slug),
                            'tanggal' => $item->tanggal,
                        ])
                )
                ->merge(
                    Halaman::query()->where('aktif', true)
                        ->where(fn ($q) => $q->where('judul', 'like', $suka)->orWhere('isi', 'like', $suka))
                        ->limit(15)->get()
                        ->map(fn ($item) => [
                            'jenis' => $item->nama_kategori,
                            'judul' => $item->judul,
                            'ringkas' => $item->ringkasan ?: potongTeks($item->isi, 150),
                            'url' => route('web.profil', $item->slug),
                            'tanggal' => $item->updated_at,
                        ])
                )
                ->merge(
                    Agenda::query()->where('aktif', true)
                        ->where(fn ($q) => $q->where('judul', 'like', $suka)->orWhere('deskripsi', 'like', $suka))
                        ->orderByDesc('tanggal_mulai')->limit(10)->get()
                        ->map(fn ($item) => [
                            'jenis' => 'Agenda',
                            'judul' => $item->judul,
                            'ringkas' => potongTeks($item->deskripsi, 150),
                            'url' => route('web.agenda'),
                            'tanggal' => $item->tanggal_mulai,
                        ])
                )
                ->merge(
                    Prestasi::query()
                        ->where(fn ($q) => $q->where('judul', 'like', $suka)->orWhere('nama_peraih', 'like', $suka))
                        ->orderByDesc('tahun')->limit(10)->get()
                        ->map(fn ($item) => [
                            'jenis' => $item->nama_kategori,
                            'judul' => $item->judul,
                            'ringkas' => trim(($item->nama_peraih ?: '').' • '.$item->nama_tingkat, ' •'),
                            'url' => route('web.prestasi', $item->kategori),
                            'tanggal' => $item->tanggal,
                        ])
                );
        }

        return $this->tampil('pencarian', [
            'kunci' => $kunci,
            'hasil' => $hasil,
        ], 'Hasil Pencarian', ['Pencarian' => null]);
    }
}
