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
 * Halaman statis: sejarah, visi misi, sambutan kepala sekolah,
 * struktur organisasi, kurikulum, informasi siswa, dll.
 */
class Halaman extends Model
{
    use HasFactory, HasUuids, LogsActivity, PunyaFile, PunyaSlug, SoftDeletes;

    protected $table = 'halamans';

    protected $fillable = ['id', 'judul', 'slug', 'kategori', 'ringkasan', 'isi', 'urutan', 'aktif', 'view'];

    protected $casts = ['id' => 'string', 'aktif' => 'boolean', 'urutan' => 'integer', 'view' => 'integer'];

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    public function scopeKategori(Builder $query, string $kategori): Builder
    {
        return $query->where('kategori', $kategori);
    }

    public function getNamaKategoriAttribute(): string
    {
        return config('sekolah.enum.kategori_halaman')[$this->kategori] ?? $this->kategori;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['judul', 'kategori'])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
