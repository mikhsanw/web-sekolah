<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Membuat slug unik otomatis dari kolom sumber saat menyimpan.
 */
trait PunyaSlug
{
    public static function bootPunyaSlug(): void
    {
        static::saving(function ($model) {
            $kolom = $model->kolomSlug();
            $sumber = $model->{$kolom} ?? null;

            if (blank($model->slug) && filled($sumber)) {
                $model->slug = $model->slugUnik(Str::slug($sumber));
            }
        });
    }

    public function kolomSlug(): string
    {
        return 'judul';
    }

    protected function slugUnik(string $dasar): string
    {
        $dasar = $dasar !== '' ? $dasar : Str::lower(Str::random(8));
        $slug = $dasar;
        $urutan = 1;

        while (
            static::withTrashed()
                ->where('slug', $slug)
                ->when($this->getKey(), fn ($query) => $query->where($this->getKeyName(), '!=', $this->getKey()))
                ->exists()
        ) {
            $slug = $dasar.'-'.(++$urutan);
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
