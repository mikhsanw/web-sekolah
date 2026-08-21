<?php

namespace App\Services;

use App\Models\Agenda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Menyiapkan data widget kalender bulanan beserta agenda di dalamnya.
 */
class KalenderSekolah
{
    /**
     * @return array{bulanKalender:int, tahunKalender:int, hariBeragenda:array<int,string>, agendaKalender:Collection}
     */
    public static function data(Request $request, int $batasAgenda = 5): array
    {
        $sekarang = Carbon::now();

        $bulan = (int) $request->integer('bulan', $sekarang->month);
        $tahun = (int) $request->integer('tahun', $sekarang->year);

        if ($bulan < 1 || $bulan > 12) {
            $bulan = $sekarang->month;
        }

        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = $sekarang->year;
        }

        $agenda = Agenda::query()
            ->where('aktif', true)
            ->padaBulan($tahun, $bulan)
            ->orderBy('tanggal_mulai')
            ->get();

        return [
            'bulanKalender' => $bulan,
            'tahunKalender' => $tahun,
            'hariBeragenda' => static::hariBeragenda($agenda),
            'agendaKalender' => $agenda->take($batasAgenda),
        ];
    }

    /**
     * Semua tanggal (Y-m-d) yang tercakup oleh agenda, termasuk rentang multi-hari.
     *
     * @param  Collection<int, Agenda>  $agenda
     * @return array<int, string>
     */
    protected static function hariBeragenda($agenda): array
    {
        $tanggal = [];

        foreach ($agenda as $item) {
            $mulai = $item->tanggal_mulai?->copy();

            if (! $mulai) {
                continue;
            }

            $selesai = $item->tanggal_selesai?->copy() ?? $mulai->copy();

            // Batasi iterasi agar agenda dengan rentang tidak wajar tidak membebani.
            if ($selesai->diffInDays($mulai) > 366) {
                $selesai = $mulai->copy()->addDays(366);
            }

            while ($mulai <= $selesai) {
                $tanggal[] = $mulai->toDateString();
                $mulai->addDay();
            }
        }

        return array_values(array_unique($tanggal));
    }
}
