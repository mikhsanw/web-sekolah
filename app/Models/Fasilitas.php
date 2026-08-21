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
 * Sarana dan prasarana sekolah.
 */
class Fasilitas extends Model
{
    use HasFactory, HasUuids, LogsActivity, PunyaFile, PunyaSlug, SoftDeletes;

    protected $table = 'fasilitas';

    protected $fillable = ['id', 'nama', 'slug', 'jumlah', 'kondisi', 'deskripsi', 'urutan', 'aktif'];

    protected $casts = ['id' => 'string', 'jumlah' => 'integer', 'aktif' => 'boolean', 'urutan' => 'integer'];

    public function kolomSlug(): string
    {
        return 'nama';
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    public function getNamaKondisiAttribute(): string
    {
        return config('sekolah.enum.kondisi_fasilitas')[$this->kondisi] ?? $this->kondisi;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['nama', 'jumlah'])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
