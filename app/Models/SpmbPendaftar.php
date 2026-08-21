<?php

namespace App\Models;

use App\Models\Concerns\PunyaFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Calon peserta didik yang mendaftar lewat SPMB online.
 */
class SpmbPendaftar extends Model
{
    use HasFactory, HasUuids, LogsActivity, PunyaFile, SoftDeletes;

    protected $table = 'spmb_pendaftars';

    protected $fillable = [
        'id', 'spmb_periode_id', 'no_pendaftaran', 'nisn', 'nik', 'nama',
        'jenis_kelamin', 'agama', 'tempat_lahir', 'tanggal_lahir', 'alamat',
        'no_hp', 'asal_sekolah', 'nama_orang_tua', 'status', 'catatan', 'terdaftar_pada',
    ];

    protected $casts = [
        'id' => 'string',
        'tanggal_lahir' => 'date',
        'terdaftar_pada' => 'datetime',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(SpmbPeriode::class, 'spmb_periode_id');
    }

    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function getNamaStatusAttribute(): string
    {
        return config('sekolah.enum.status_spmb')[$this->status] ?? $this->status;
    }

    public function getWarnaStatusAttribute(): string
    {
        return config('sekolah.enum.warna_spmb')[$this->status] ?? 'secondary';
    }

    public function getNamaJenisKelaminAttribute(): string
    {
        return config('sekolah.enum.jenis_kelamin')[$this->jenis_kelamin] ?? '-';
    }

    public function getTtlAttribute(): string
    {
        return trim(($this->tempat_lahir ?: '-').', '.($this->tanggal_lahir?->locale('id')->isoFormat('D MMMM Y') ?? '-'));
    }

    /**
     * Nomor pendaftaran: SPMB-{tahun}-{urut 4 digit}.
     */
    public static function buatNomorPendaftaran(SpmbPeriode $periode): string
    {
        $tahun = Str::before($periode->tahun_ajaran, '/') ?: date('Y');
        $prefix = 'SPMB-'.$tahun.'-';

        $terakhir = static::withTrashed()
            ->where('spmb_periode_id', $periode->id)
            ->where('no_pendaftaran', 'like', $prefix.'%')
            ->orderByDesc('no_pendaftaran')
            ->value('no_pendaftaran');

        $urut = $terakhir ? ((int) Str::afterLast($terakhir, '-')) + 1 : 1;

        return $prefix.str_pad((string) $urut, 4, '0', STR_PAD_LEFT);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['no_pendaftaran', 'nama', 'status'])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
