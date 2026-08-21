<?php

namespace App\Models;

use App\Models\Concerns\PunyaFile;
use App\Models\Concerns\PunyaSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Galeri foto dan video kegiatan sekolah.
 */
class Galeri extends Model
{
    use HasFactory, HasUuids, LogsActivity, PunyaFile, PunyaSlug, SoftDeletes;

    protected $table = 'galeris';

    protected $fillable = ['id', 'judul', 'slug', 'kategori', 'tanggal', 'video_url', 'deskripsi', 'urutan', 'aktif'];

    protected $casts = ['id' => 'string', 'tanggal' => 'date', 'aktif' => 'boolean', 'urutan' => 'integer'];

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    public function scopeFoto(Builder $query): Builder
    {
        return $query->where('kategori', 'foto');
    }

    public function scopeVideo(Builder $query): Builder
    {
        return $query->where('kategori', 'video');
    }

    /**
     * URL embed YouTube dari berbagai bentuk tautan yang biasa disalin admin.
     */
    public function getVideoEmbedAttribute(): ?string
    {
        if (blank($this->video_url)) {
            return null;
        }

        $url = $this->video_url;

        if (Str::contains($url, 'youtu.be/')) {
            $kode = Str::before(Str::afterLast($url, 'youtu.be/'), '?');

            return 'https://www.youtube.com/embed/'.$kode;
        }

        if (Str::contains($url, 'watch?v=')) {
            $kode = Str::before(Str::after($url, 'watch?v='), '&');

            return 'https://www.youtube.com/embed/'.$kode;
        }

        if (Str::contains($url, '/shorts/')) {
            $kode = Str::before(Str::afterLast($url, '/shorts/'), '?');

            return 'https://www.youtube.com/embed/'.$kode;
        }

        return $url;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['judul', 'kategori'])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
