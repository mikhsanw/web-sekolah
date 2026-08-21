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
 * Periode / angkatan pengumuman kelulusan (mis. 2025/2026).
 */
class KelulusanPeriode extends Model
{
    use HasFactory, HasUuids, LogsActivity, PunyaSlug, SoftDeletes;

    protected $table = 'kelulusan_periodes';

    protected $fillable = [
        'id', 'tahun_ajaran', 'judul', 'slug', 'tanggal_pengumuman',
        'pengambilan_mulai', 'pengambilan_selesai', 'tempat_pengambilan',
        'keterangan', 'dibuka', 'aktif',
    ];

    protected $casts = [
        'id' => 'string',
        'tanggal_pengumuman' => 'datetime',
        'pengambilan_mulai' => 'date',
        'pengambilan_selesai' => 'date',
        'dibuka' => 'boolean',
        'aktif' => 'boolean',
    ];

    public function kolomSlug(): string
    {
        return 'tahun_ajaran';
    }

    public function kelulusan(): HasMany
    {
        return $this->hasMany(Kelulusan::class, 'kelulusan_periode_id');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    /**
     * Pengumuman baru bisa dibuka bila flag dibuka aktif dan waktunya sudah tiba.
     */
    public function getSudahDiumumkanAttribute(): bool
    {
        if (! $this->dibuka) {
            return false;
        }

        return blank($this->tanggal_pengumuman) || $this->tanggal_pengumuman->isPast();
    }

    public function getInfoPengambilanAttribute(): ?string
    {
        if (blank($this->pengambilan_mulai)) {
            return null;
        }

        $mulai = $this->pengambilan_mulai->locale('id')->isoFormat('D MMMM Y');

        if ($this->pengambilan_selesai && ! $this->pengambilan_selesai->isSameDay($this->pengambilan_mulai)) {
            return $mulai.' s.d. '.$this->pengambilan_selesai->locale('id')->isoFormat('D MMMM Y');
        }

        return $mulai;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['tahun_ajaran', 'dibuka'])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
