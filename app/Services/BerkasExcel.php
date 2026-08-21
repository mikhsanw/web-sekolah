<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Pembaca dan penulis berkas Excel (.xlsx) untuk modul kelulusan & SPMB.
 */
class BerkasExcel
{
    /**
     * Kirim data sebagai unduhan .xlsx.
     *
     * @param  array<int, string>  $judulKolom
     * @param  iterable<int, array<int, mixed>>  $baris
     */
    public static function unduh(string $namaBerkas, array $judulKolom, iterable $baris, ?string $judulLembar = null): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $lembar = $spreadsheet->getActiveSheet();
        $lembar->setTitle(mb_substr($judulLembar ?? 'Data', 0, 31));

        $lembar->fromArray($judulKolom, null, 'A1');

        $nomorBaris = 2;
        foreach ($baris as $isi) {
            $lembar->fromArray(array_values($isi), null, 'A'.$nomorBaris);
            $nomorBaris++;
        }

        $kolomTerakhir = $lembar->getHighestColumn();
        $rentangJudul = 'A1:'.$kolomTerakhir.'1';

        $lembar->getStyle($rentangJudul)->getFont()->setBold(true);
        $lembar->getStyle($rentangJudul)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('0F5132');
        $lembar->getStyle($rentangJudul)->getFont()->getColor()->setRGB('FFFFFF');
        $lembar->getStyle($rentangJudul)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        if ($nomorBaris > 2) {
            $lembar->getStyle('A1:'.$kolomTerakhir.($nomorBaris - 1))
                ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        foreach (range('A', $kolomTerakhir) as $kolom) {
            $lembar->getColumnDimension($kolom)->setAutoSize(true);
        }

        $lembar->freezePane('A2');

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $namaBerkas, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    /**
     * Baca berkas Excel/CSV menjadi array asosiatif memakai baris pertama sebagai judul kolom.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function baca(UploadedFile $berkas): array
    {
        $pembaca = IOFactory::createReaderForFile($berkas->getRealPath());
        $pembaca->setReadDataOnly(false);
        $lembar = $pembaca->load($berkas->getRealPath())->getActiveSheet();

        $baris = $lembar->toArray(null, true, false, false);

        if (empty($baris)) {
            return [];
        }

        $judul = array_map(
            fn ($kolom) => Str::of((string) $kolom)->trim()->lower()->replace(' ', '_')->replace('.', '')->toString(),
            array_shift($baris)
        );

        $hasil = [];

        foreach ($baris as $isi) {
            $item = [];

            foreach ($judul as $index => $nama) {
                if ($nama === '') {
                    continue;
                }

                $nilai = $isi[$index] ?? null;
                $item[$nama] = is_string($nilai) ? trim($nilai) : $nilai;
            }

            // Lewati baris yang seluruh selnya kosong.
            if (collect($item)->filter(fn ($nilai) => $nilai !== null && $nilai !== '')->isNotEmpty()) {
                $hasil[] = $item;
            }
        }

        return $hasil;
    }

    /**
     * Ubah nilai sel menjadi tanggal Y-m-d (mendukung serial Excel maupun teks).
     */
    public static function keTanggal(mixed $nilai): ?string
    {
        if ($nilai === null || $nilai === '') {
            return null;
        }

        if (is_numeric($nilai)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $nilai)->format('Y-m-d');
            } catch (\Throwable) {
                return null;
            }
        }

        $teks = trim((string) $nilai);

        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'm/d/Y', 'Y/m/d', 'd.m.Y'] as $format) {
            $tanggal = \DateTime::createFromFormat($format, $teks);

            if ($tanggal && $tanggal->format($format) === $teks) {
                return $tanggal->format('Y-m-d');
            }
        }

        try {
            return Carbon::parse($teks)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }
}
