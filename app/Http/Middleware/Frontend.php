<?php

namespace App\Http\Middleware;

use App\Models\KelulusanPeriode;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membagikan data yang dipakai seluruh halaman publik website sekolah
 * (identitas sekolah, menu navigasi, folder aset).
 *
 * Ditulis tahan-gagal: sebelum `migrate --seed` dijalankan pun halaman
 * depan harus tetap bisa tampil.
 */
class Frontend
{
    public function handle(Request $request, Closure $next): Response
    {
        View::share([
            'menuWeb' => $this->navigasi(),
            'templateWeb' => config('master.app.web.template'),
            'identitas' => $this->identitas(),
        ]);

        return $next($request);
    }

    /**
     * Bangun navigasi dari config/sekolah.php lalu isi bagian dinamisnya.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function navigasi(): array
    {
        $menu = [];

        foreach ((array) config('sekolah.navigasi', []) as $item) {
            $anak = [];

            foreach ($item['anak'] ?? [] as $sub) {
                $anak[] = $this->itemMenu($sub);
            }

            if (($item['dinamis'] ?? null) === 'kelulusan') {
                $anak = array_merge($anak, $this->menuKelulusan());
            }

            $menu[] = array_merge($this->itemMenu($item), ['anak' => $anak]);
        }

        return $menu;
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    protected function itemMenu(array $item): array
    {
        $rute = $item['rute'] ?? null;
        $params = $item['params'] ?? [];
        $ada = $rute && Route::has($rute);

        return [
            'label' => $item['label'],
            'url' => $ada ? route($rute, $params) : ($item['url'] ?? '#'),
            'aktif' => $ada && $this->sedangAktif($rute, $params),
            'anak' => [],
        ];
    }

    /**
     * @param  array<string, mixed>  $params
     */
    protected function sedangAktif(string $rute, array $params = []): bool
    {
        if (! request()->routeIs($rute)) {
            return false;
        }

        foreach ($params as $kunci => $nilai) {
            $aktual = request()->route($kunci) ?? request()->query($kunci);

            if (is_object($aktual)) {
                $aktual = $aktual->slug ?? $aktual->getKey();
            }

            if ((string) $aktual !== (string) $nilai) {
                return false;
            }
        }

        return true;
    }

    /**
     * Angkatan kelulusan yang aktif tampil sebagai submenu.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function menuKelulusan(): array
    {
        try {
            return KelulusanPeriode::query()
                ->where('aktif', true)
                ->orderByDesc('tahun_ajaran')
                ->get()
                ->map(fn (KelulusanPeriode $periode) => [
                    'label' => 'Angkatan '.$periode->tahun_ajaran,
                    'url' => route('web.kelulusan.periode', $periode->slug),
                    'aktif' => request()->routeIs('web.kelulusan.*')
                        && request()->route('periode') === $periode->slug,
                    'anak' => [],
                ])
                ->all();
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function identitas(): array
    {
        $identitas = config('sekolah.identitas');
        $kontak = config('sekolah.kontak');

        try {
            return [
                'nama' => pengaturan('nama_sekolah', $identitas['nama']),
                'nama_panjang' => pengaturan('nama_panjang', $identitas['nama_panjang']),
                'npsn' => pengaturan('npsn', $identitas['npsn']),
                'alamat' => pengaturan('alamat', $identitas['alamat']),
                'telepon' => pengaturan('telepon', $identitas['telepon']),
                'email' => pengaturan('email', $identitas['email']),
                'moto' => pengaturan('moto', $identitas['moto']),
                'kepala_sekolah' => pengaturan('kepala_sekolah', $identitas['kepala_sekolah']),
                'facebook' => pengaturan('facebook', $kontak['facebook']),
                'instagram' => pengaturan('instagram', $kontak['instagram']),
                'youtube' => pengaturan('youtube', $kontak['youtube']),
                'whatsapp' => nomorWhatsapp(pengaturan('whatsapp', $kontak['whatsapp'])),
                'maps_embed' => pengaturan('maps_embed', $kontak['maps_embed']),
                'logo' => pengaturanBerkas('logo'),
                'hymne' => pengaturanBerkas('hymne'),
                'warna_utama' => pengaturan('warna_utama', config('sekolah.tampilan.warna_utama')),
                'warna_aksen' => pengaturan('warna_aksen', config('sekolah.tampilan.warna_aksen')),
                'teks_footer' => pengaturan('teks_footer'),
            ];
        } catch (\Throwable) {
            return array_merge($identitas, $kontak, [
                'logo' => null,
                'hymne' => null,
                'warna_utama' => config('sekolah.tampilan.warna_utama'),
                'warna_aksen' => config('sekolah.tampilan.warna_aksen'),
                'teks_footer' => null,
            ]);
        }
    }
}
