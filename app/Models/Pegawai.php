<?php

namespace App\Models;

use App\Models\Concerns\PunyaFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Guru dan Tenaga Kependidikan (GTK).
 */
class Pegawai extends Model
{
    use HasFactory, HasUuids, LogsActivity, PunyaFile, SoftDeletes;

    protected $fillable = [
        'id', 'nama', 'nip', 'nuptk', 'jenis', 'jabatan', 'mata_pelajaran',
        'jenis_kelamin', 'pendidikan_terakhir', 'status_kepegawaian', 'email', 'urutan', 'aktif',
    ];

    protected $casts = ['id' => 'string', 'aktif' => 'boolean', 'urutan' => 'integer'];

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    public function scopeGuru(Builder $query): Builder
    {
        return $query->where('jenis', 'guru');
    }

    public function scopeTendik(Builder $query): Builder
    {
        return $query->where('jenis', 'tendik');
    }

    public function getNamaJenisAttribute(): string
    {
        return config('sekolah.enum.jenis_pegawai')[$this->jenis] ?? $this->jenis;
    }

    public function getNamaJenisKelaminAttribute(): string
    {
        return config('sekolah.enum.jenis_kelamin')[$this->jenis_kelamin] ?? '-';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['nama', 'jenis', 'jabatan'])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
