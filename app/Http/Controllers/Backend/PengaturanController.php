<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Pengaturan Website: identitas sekolah, kontak, media (logo/hymne), dan tampilan.
 */
class PengaturanController extends Controller
{
    public function index()
    {
        $this->pastikanTerisi();

        return view($this->view.'.index', [
            'kelompok' => Pengaturan::orderBy('urutan')->get()->groupBy('kelompok'),
            'namaKelompok' => $this->namaKelompok(),
        ]);
    }

    public function update(Request $request)
    {
        $this->pastikanTerisi();

        $daftar = Pengaturan::all();

        $aturan = [];
        foreach ($daftar as $item) {
            $aturan['nilai.'.$item->kunci] = match ($item->tipe) {
                'url' => ['nullable', 'string', 'max:255'],
                'color' => ['nullable', 'string', 'max:20'],
                'textarea' => ['nullable', 'string', 'max:2000'],
                default => ['nullable', 'string', 'max:500'],
            };

            if ($item->tipe === 'image') {
                $aturan['berkas.'.$item->kunci] = ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'];
            }

            if ($item->tipe === 'audio') {
                $aturan['berkas.'.$item->kunci] = ['nullable', 'file', 'mimes:mp3,mpga,wav,ogg', 'max:10240'];
            }
        }

        $request->validate($aturan);

        DB::transaction(function () use ($daftar, $request) {
            foreach ($daftar as $item) {
                if (in_array($item->tipe, ['image', 'audio'], true)) {
                    if ($request->hasFile('berkas.'.$item->kunci)) {
                        $item->simpanBerkas($request->file('berkas.'.$item->kunci), $item->kunci, 'pengaturan');
                    }

                    continue;
                }

                $item->update(['nilai' => $request->input('nilai.'.$item->kunci)]);
            }
        });

        Pengaturan::bersihkanCache();

        return redirect()->route('pengaturan.index')->with('sukses', 'Pengaturan website berhasil disimpan.');
    }

    /**
     * Hapus berkas (logo, hymne, dsb) pada satu pengaturan.
     */
    public function hapusBerkas(string $kunci)
    {
        $item = Pengaturan::where('kunci', $kunci)->firstOrFail();

        $item->files()->where('alias', $kunci)->get()->each->forceDelete();

        Pengaturan::bersihkanCache();

        return redirect()->route('pengaturan.index')->with('sukses', 'Berkas berhasil dihapus.');
    }

    /**
     * Lengkapi baris pengaturan yang belum ada (mis. setelah menambah definisi baru).
     */
    protected function pastikanTerisi(): void
    {
        foreach (Pengaturan::definisi() as $definisi) {
            Pengaturan::firstOrCreate(
                ['kunci' => $definisi['kunci']],
                [
                    'kelompok' => $definisi['kelompok'],
                    'label' => $definisi['label'],
                    'tipe' => $definisi['tipe'],
                    'nilai' => $definisi['nilai'] ?? null,
                    'keterangan' => $definisi['keterangan'] ?? null,
                    'urutan' => $definisi['urutan'] ?? 0,
                ]
            );
        }

        Pengaturan::bersihkanCache();
    }

    /**
     * @return array<string, string>
     */
    protected function namaKelompok(): array
    {
        return [
            'identitas' => 'Identitas Sekolah',
            'kontak' => 'Kontak & Media Sosial',
            'media' => 'Logo, Hymne & Gambar',
            'tampilan' => 'Tampilan Website',
        ];
    }
}
