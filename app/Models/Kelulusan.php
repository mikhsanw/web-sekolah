<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Data kelulusan siswa per angkatan.
 * Siswa mengakses datanya memakai NISN + tanggal lahir.
 */
class Kelulusan extends Model
{
    use HasFactory, HasUuids, LogsActivity, SoftDeletes;

    protected $table = 'kelulusans';

    protected $fillable = [
        'id', 'kelulusan_periode_id', 'nisn', 'nis', 'nama', 'tanggal_lahir',
        'kelas', 'status', 'keterangan', 'tanggal_pengambilan',
    ];

    protected $casts = [
        'id' => 'string',
        'tanggal_lahir' => 'date',
        'tanggal_pengambilan' => 'date',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(KelulusanPeriode::class, 'kelulusan_periode_id');
    }

    public function getLulusAttribute(): bool
    {
        return $this->status === 'lulus';
    }

    public function getNamaStatusAttribute(): string
    {
        return config('sekolah.enum.status_kelulusan')[$this->status] ?? $this->status;
    }

    public function getWarnaStatusAttribute(): string
    {
        return config('sekolah.enum.warna_kelulusan')[$this->status] ?? 'secondary';
    }

    /**
     * Tanggal pengambilan administrasi: per siswa, jatuh ke pengaturan periode.
     */
    public function getInfoPengambilanAttribute(): ?string
    {
        if ($this->tanggal_pengambilan) {
            return $this->tanggal_pengambilan->locale('id')->isoFormat('dddd, D MMMM Y');
        }

        return $this->periode?->info_pengambilan;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['nisn', 'nama', 'status'])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
