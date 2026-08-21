<?php

namespace App\Models;

use App\Models\Concerns\PunyaFile;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Pengaturan website sekolah (identitas, kontak, media, tampilan).
 *
 * Nilai default diambil dari config/sekolah.php sehingga aplikasi tetap
 * bisa berjalan sebelum tabel ini di-seed.
 */
class Pengaturan extends Model
{
    use HasFactory, HasUuids, LogsActivity, PunyaFile, SoftDeletes;

    public const CACHE_KEY = 'sekolah.pengaturan';

    protected $fillable = ['id', 'kelompok', 'kunci', 'label', 'nilai', 'tipe', 'keterangan', 'urutan'];

    protected $casts = ['id' => 'string', 'urutan' => 'integer'];

    /**
     * Daftar pengaturan bawaan: dipakai seeder dan form di admin.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function definisi(): array
    {
        $identitas = config('sekolah.identitas');
        $kontak = config('sekolah.kontak');
        $tampilan = config('sekolah.tampilan');

        return [
            ['kelompok' => 'identitas', 'kunci' => 'nama_sekolah', 'label' => 'Nama Sekolah', 'tipe' => 'text', 'nilai' => $identitas['nama'], 'urutan' => 1],
            ['kelompok' => 'identitas', 'kunci' => 'nama_panjang', 'label' => 'Nama Lengkap Sekolah', 'tipe' => 'text', 'nilai' => $identitas['nama_panjang'], 'urutan' => 2],
            ['kelompok' => 'identitas', 'kunci' => 'npsn', 'label' => 'NPSN', 'tipe' => 'text', 'nilai' => $identitas['npsn'], 'urutan' => 3],
            ['kelompok' => 'identitas', 'kunci' => 'status_sekolah', 'label' => 'Status Sekolah', 'tipe' => 'text', 'nilai' => $identitas['status'], 'urutan' => 4],
            ['kelompok' => 'identitas', 'kunci' => 'akreditasi', 'label' => 'Akreditasi', 'tipe' => 'text', 'nilai' => $identitas['akreditasi'], 'urutan' => 5],
            ['kelompok' => 'identitas', 'kunci' => 'kepala_sekolah', 'label' => 'Nama Kepala Sekolah', 'tipe' => 'text', 'nilai' => $identitas['kepala_sekolah'], 'urutan' => 6],
            ['kelompok' => 'identitas', 'kunci' => 'nip_kepala_sekolah', 'label' => 'NIP Kepala Sekolah', 'tipe' => 'text', 'nilai' => $identitas['nip_kepala_sekolah'], 'urutan' => 7],
            ['kelompok' => 'identitas', 'kunci' => 'moto', 'label' => 'Moto Sekolah', 'tipe' => 'text', 'nilai' => $identitas['moto'], 'urutan' => 8],
            ['kelompok' => 'identitas', 'kunci' => 'alamat', 'label' => 'Alamat', 'tipe' => 'textarea', 'nilai' => $identitas['alamat'], 'urutan' => 9],
            ['kelompok' => 'identitas', 'kunci' => 'kecamatan', 'label' => 'Kecamatan', 'tipe' => 'text', 'nilai' => $identitas['kecamatan'], 'urutan' => 10],
            ['kelompok' => 'identitas', 'kunci' => 'kabupaten', 'label' => 'Kabupaten/Kota', 'tipe' => 'text', 'nilai' => $identitas['kabupaten'], 'urutan' => 11],
            ['kelompok' => 'identitas', 'kunci' => 'provinsi', 'label' => 'Provinsi', 'tipe' => 'text', 'nilai' => $identitas['provinsi'], 'urutan' => 12],
            ['kelompok' => 'identitas', 'kunci' => 'kode_pos', 'label' => 'Kode Pos', 'tipe' => 'text', 'nilai' => $identitas['kode_pos'], 'urutan' => 13],
            ['kelompok' => 'identitas', 'kunci' => 'telepon', 'label' => 'Telepon', 'tipe' => 'text', 'nilai' => $identitas['telepon'], 'urutan' => 14],
            ['kelompok' => 'identitas', 'kunci' => 'email', 'label' => 'Email', 'tipe' => 'text', 'nilai' => $identitas['email'], 'urutan' => 15],

            ['kelompok' => 'kontak', 'kunci' => 'facebook', 'label' => 'Facebook', 'tipe' => 'url', 'nilai' => $kontak['facebook'], 'keterangan' => 'Contoh: https://facebook.com/smpn5mandau', 'urutan' => 1],
            ['kelompok' => 'kontak', 'kunci' => 'instagram', 'label' => 'Instagram', 'tipe' => 'url', 'nilai' => $kontak['instagram'], 'keterangan' => 'Contoh: https://instagram.com/smpn5mandau', 'urutan' => 2],
            ['kelompok' => 'kontak', 'kunci' => 'youtube', 'label' => 'YouTube', 'tipe' => 'url', 'nilai' => $kontak['youtube'], 'keterangan' => 'Contoh: https://youtube.com/@smpn5mandau', 'urutan' => 3],
            ['kelompok' => 'kontak', 'kunci' => 'whatsapp', 'label' => 'WhatsApp', 'tipe' => 'text', 'nilai' => $kontak['whatsapp'], 'keterangan' => 'Nomor format internasional tanpa tanda +, contoh: 6281234567890', 'urutan' => 4],
            ['kelompok' => 'kontak', 'kunci' => 'maps_embed', 'label' => 'Google Maps (URL Embed)', 'tipe' => 'textarea', 'nilai' => $kontak['maps_embed'], 'keterangan' => 'Salin bagian src dari kode embed Google Maps', 'urutan' => 5],

            ['kelompok' => 'media', 'kunci' => 'logo', 'label' => 'Logo Sekolah', 'tipe' => 'image', 'urutan' => 1],
            ['kelompok' => 'media', 'kunci' => 'favicon', 'label' => 'Favicon', 'tipe' => 'image', 'urutan' => 2],
            ['kelompok' => 'media', 'kunci' => 'hymne', 'label' => 'Hymne Sekolah (MP3)', 'tipe' => 'audio', 'urutan' => 3],
            ['kelompok' => 'media', 'kunci' => 'struktur_organisasi', 'label' => 'Bagan Struktur Organisasi', 'tipe' => 'image', 'urutan' => 4],
            ['kelompok' => 'media', 'kunci' => 'peta_sekolah', 'label' => 'Denah / Peta Sekolah', 'tipe' => 'image', 'urutan' => 5],

            ['kelompok' => 'tampilan', 'kunci' => 'warna_utama', 'label' => 'Warna Utama', 'tipe' => 'color', 'nilai' => $tampilan['warna_utama'], 'urutan' => 1],
            ['kelompok' => 'tampilan', 'kunci' => 'warna_aksen', 'label' => 'Warna Aksen', 'tipe' => 'color', 'nilai' => $tampilan['warna_aksen'], 'urutan' => 2],
            ['kelompok' => 'tampilan', 'kunci' => 'teks_footer', 'label' => 'Teks Footer', 'tipe' => 'textarea', 'nilai' => null, 'urutan' => 3],
        ];
    }

    /**
     * Seluruh pengaturan dalam bentuk array kunci => nilai (di-cache).
     *
     * @return array<string, mixed>
     */
    public static function semua(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            try {
                return static::query()->pluck('nilai', 'kunci')->toArray();
            } catch (\Throwable) {
                return [];
            }
        });
    }

    public static function nilai(string $kunci, mixed $default = null): mixed
    {
        $nilai = static::semua()[$kunci] ?? null;

        return filled($nilai) ? $nilai : ($default ?? static::bawaan($kunci));
    }

    /**
     * URL berkas pada pengaturan bertipe file/gambar (di-cache).
     */
    public static function berkasPublik(string $kunci, ?string $default = null): ?string
    {
        $daftar = Cache::rememberForever(self::CACHE_KEY.'.berkas', function () {
            try {
                return static::query()
                    ->with('files')
                    ->get()
                    ->mapWithKeys(fn ($item) => [$item->kunci => $item->berkasUrl($item->kunci)])
                    ->filter()
                    ->toArray();
            } catch (\Throwable) {
                return [];
            }
        });

        return $daftar[$kunci] ?? $default;
    }

    protected static function bawaan(string $kunci): mixed
    {
        foreach (static::definisi() as $item) {
            if ($item['kunci'] === $kunci) {
                return $item['nilai'] ?? null;
            }
        }

        return null;
    }

    public static function bersihkanCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY.'.berkas');
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::bersihkanCache());
        static::deleted(fn () => static::bersihkanCache());
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kunci', 'nilai'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
