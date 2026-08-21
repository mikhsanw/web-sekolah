<?php

namespace App\Models;

use App\Models\Concerns\PunyaFile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Agenda / kalender kegiatan sekolah (termasuk agenda tahunan).
 */
class Agenda extends Model
{
    use HasFactory, HasUuids, LogsActivity, PunyaFile, SoftDeletes;

    protected $fillable = ['id', 'judul', 'jenis', 'tanggal_mulai', 'tanggal_selesai', 'waktu', 'lokasi', 'deskripsi', 'aktif'];

    protected $casts = [
        'id' => 'string',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'aktif' => 'boolean',
    ];

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    public function scopeMendatang(Builder $query): Builder
    {
        return $query->whereDate('tanggal_mulai', '>=', now()->toDateString());
    }

    public function scopePadaBulan(Builder $query, int $tahun, int $bulan): Builder
    {
        $awal = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $akhir = (clone $awal)->endOfMonth();

        return $query->where(function (Builder $q) use ($awal, $akhir) {
            $q->whereBetween('tanggal_mulai', [$awal->toDateString(), $akhir->toDateString()])
                ->orWhereBetween('tanggal_selesai', [$awal->toDateString(), $akhir->toDateString()])
                ->orWhere(function (Builder $sub) use ($awal, $akhir) {
                    $sub->where('tanggal_mulai', '<=', $awal->toDateString())
                        ->whereNotNull('tanggal_selesai')
                        ->where('tanggal_selesai', '>=', $akhir->toDateString());
                });
        });
    }

    public function getNamaJenisAttribute(): string
    {
        return config('sekolah.enum.jenis_agenda')[$this->jenis] ?? $this->jenis;
    }

    public function getWarnaAttribute(): string
    {
        return config('sekolah.enum.warna_agenda')[$this->jenis] ?? 'primary';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['judul', 'tanggal_mulai'])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
