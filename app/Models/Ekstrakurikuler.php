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

class Ekstrakurikuler extends Model
{
    use HasFactory, HasUuids, LogsActivity, PunyaFile, PunyaSlug, SoftDeletes;

    protected $table = 'ekstrakurikulers';

    protected $fillable = ['id', 'nama', 'slug', 'pembina', 'jadwal', 'tempat', 'deskripsi', 'urutan', 'aktif'];

    protected $casts = ['id' => 'string', 'aktif' => 'boolean', 'urutan' => 'integer'];

    public function kolomSlug(): string
    {
        return 'nama';
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['nama', 'pembina'])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
