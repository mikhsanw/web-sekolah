<?php

namespace App\Models;

use App\Models\Concerns\PunyaFile;
use App\Models\Concerns\PunyaSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Prestasi siswa maupun guru.
 */
class Prestasi extends Model
{
    use HasFactory, HasUuids, LogsActivity, PunyaFile, PunyaSlug, SoftDeletes;

    protected $table = 'prestasis';

    protected $fillable = [
        'id', 'judul', 'slug', 'kategori', 'nama_peraih', 'tingkat',
        'peringkat', 'penyelenggara', 'tahun', 'tanggal', 'deskripsi',
    ];

    protected $casts = ['id' => 'string', 'tanggal' => 'date'];

    public function scopeKategori(Builder $query, string $kategori): Builder
    {
        return $query->where('kategori', $kategori);
    }

    public function getNamaKategoriAttribute(): string
    {
        return config('sekolah.enum.kategori_prestasi')[$this->kategori] ?? $this->kategori;
    }

    public function getNamaTingkatAttribute(): string
    {
        return config('sekolah.enum.tingkat_prestasi')[$this->tingkat] ?? $this->tingkat;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['judul', 'kategori'])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
