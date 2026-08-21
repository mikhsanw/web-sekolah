<?php

namespace App\Models;

use App\Models\Concerns\PunyaSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Gelombang / periode SPMB (Seleksi Penerimaan Murid Baru).
 */
class SpmbPeriode extends Model
{
    use HasFactory, HasUuids, LogsActivity, PunyaSlug, SoftDeletes;

    protected $table = 'spmb_periodes';

    protected $fillable = [
        'id', 'tahun_ajaran', 'judul', 'slug', 'tanggal_buka', 'tanggal_tutup',
        'kuota', 'informasi', 'syarat', 'dibuka', 'aktif',
    ];

    protected $casts = [
        'id' => 'string',
        'tanggal_buka' => 'datetime',
        'tanggal_tutup' => 'datetime',
        'kuota' => 'integer',
        'dibuka' => 'boolean',
        'aktif' => 'boolean',
    ];

    public function kolomSlug(): string
    {
        return 'tahun_ajaran';
    }

    public function pendaftar(): HasMany
    {
        return $this->hasMany(SpmbPendaftar::class, 'spmb_periode_id');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    /**
     * Pendaftaran hanya menerima input bila flag dibuka aktif dan
     * waktu sekarang berada dalam rentang tanggal buka/tutup.
     */
    public function getSedangDibukaAttribute(): bool
    {
        if (! $this->dibuka || ! $this->aktif) {
            return false;
        }

        if ($this->tanggal_buka && $this->tanggal_buka->isFuture()) {
            return false;
        }

        if ($this->tanggal_tutup && $this->tanggal_tutup->isPast()) {
            return false;
        }

        return true;
    }

    public function getSisaKuotaAttribute(): ?int
    {
        if (blank($this->kuota)) {
            return null;
        }

        return max(0, $this->kuota - $this->pendaftar()->count());
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['tahun_ajaran', 'dibuka'])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
