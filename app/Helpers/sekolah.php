<?php

use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Support\Str;

if (! function_exists('pengaturan')) {
    /**
     * Ambil nilai pengaturan website sekolah.
     * Urutan sumber: tabel pengaturans -> default definisi -> $default.
     */
    function pengaturan(string $kunci, mixed $default = null): mixed
    {
        $nilai = Pengaturan::nilai($kunci);

        return filled($nilai) ? $nilai : $default;
    }
}

if (! function_exists('pengaturanBerkas')) {
    /**
     * URL publik berkas pengaturan (logo, favicon, hymne, dsb).
     */
    function pengaturanBerkas(string $kunci, ?string $default = null): ?string
    {
        return Pengaturan::berkasPublik($kunci, $default);
    }
}

if (! function_exists('namaSekolah')) {
    function namaSekolah(): string
    {
        return (string) pengaturan('nama_sekolah', config('sekolah.identitas.nama'));
    }
}

if (! function_exists('logoSekolah')) {
    function logoSekolah(): string
    {
        return pengaturanBerkas('logo') ?? asset(config('master.app.web.template').'/img/logo.svg');
    }
}

if (! function_exists('tanggalIndo')) {
    /**
     * Format tanggal Indonesia, mis. "Kamis, 21 Agustus 2026".
     */
    function tanggalIndo(mixed $tanggal, string $format = 'dddd, D MMMM Y'): ?string
    {
        if (blank($tanggal)) {
            return null;
        }

        try {
            return Carbon::parse($tanggal)->locale('id')->isoFormat($format);
        } catch (Throwable) {
            return null;
        }
    }
}

if (! function_exists('potongTeks')) {
    /**
     * Ringkas konten HTML menjadi teks polos sepanjang $panjang karakter.
     */
    function potongTeks(?string $konten, int $panjang = 160): string
    {
        $teks = trim(preg_replace('/\s+/', ' ', strip_tags((string) $konten)));

        return Str::limit($teks, $panjang);
    }
}

if (! function_exists('nomorWhatsapp')) {
    /**
     * Normalisasi nomor WhatsApp menjadi format internasional tanpa tanda +.
     */
    function nomorWhatsapp(?string $nomor = null): ?string
    {
        $nomor = $nomor ?? pengaturan('whatsapp');
        $nomor = preg_replace('/[^0-9]/', '', (string) $nomor);

        if (blank($nomor)) {
            return null;
        }

        if (str_starts_with($nomor, '0')) {
            $nomor = '62'.substr($nomor, 1);
        }

        return $nomor;
    }
}
