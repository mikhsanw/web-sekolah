<?php

namespace App\Models\Concerns;

use App\Models\File;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Relasi & helper berkas (mengikuti pola morph File pada master template).
 */
trait PunyaFile
{
    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    /**
     * Ambil satu berkas berdasarkan alias (mis. "gambar", "foto", "hymne").
     */
    public function berkas(string $alias = 'gambar'): ?File
    {
        if ($this->relationLoaded('files')) {
            return $this->files->firstWhere('alias', $alias);
        }

        return $this->files()->where('alias', $alias)->first();
    }

    /**
     * URL publik berkas (dipakai di halaman depan) dengan fallback.
     */
    public function berkasUrl(string $alias = 'gambar', ?string $default = null): ?string
    {
        $file = $this->berkas($alias);

        return $file ? $file->link_public_stream : $default;
    }

    /**
     * Simpan / perbarui berkas pada alias tertentu.
     */
    public function simpanBerkas(UploadedFile $upload, string $alias = 'gambar', ?string $folder = null): File
    {
        $disk = config('filesystems.default');
        $folder = $folder ?: $this->getTable();
        $target = Storage::disk($disk)->putFile(
            $folder.'/'.date('Y').'/'.date('m').'/'.date('d'),
            $upload
        );

        return $this->files()->updateOrCreate(
            ['alias' => $alias],
            [
                'data' => [
                    'name' => $upload->getClientOriginalName(),
                    'disk' => $disk,
                    'target' => $target,
                ],
            ]
        );
    }
}
