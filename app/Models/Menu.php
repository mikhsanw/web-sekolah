<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Menu extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = ['id', 'parent_id', 'title', 'subtitle', 'code', 'url', 'model', 'icon', 'type', 'show', 'active', 'sort'];

    protected $casts = [
        'show' => 'boolean',
        'active' => 'boolean',
        'id' => 'string',
        'parent_id' => 'string',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function parent(): object
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->where('active', true)->orderBy('sort', 'asc');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function scopeSort($query): object
    {
        return $query->orderBy('sort', 'asc');
    }

    public function scopeActive($query): object
    {
        return $query->where('active', true);
    }

    public function scopeShow($query): object
    {
        return $query->where('show', true);
    }

    public function getModelAttribute(): string
    {
        $namespace = Str::replace('/', '\\', config('master.app.root.model'));
        $model = $this->attributes['model'] ?? '';

        if ($model === '') {
            return $namespace.'\\';
        }

        // Nilai bisa tersimpan sebagai nama kelas polos ("Berita") maupun
        // sudah lengkap dengan namespace ("App\Models\Berita"), jadi jangan
        // menambahkan awalan namespace dua kali.
        return Str::startsWith($model, $namespace.'\\') ? $model : $namespace.'\\'.$model;
    }

    public function active($kode)
    {
        static $currentMenu = null;
        static $currentCode = null;

        if ($currentMenu === null) {
            $currentCode = explode('.', Route::currentRouteName())[0];
            $currentMenu = $this->whereCode($currentCode)->first();
        }

        if (!$currentMenu) {
            return '';
        }

        return $kode == $currentCode ? 'active' : $this->getParent($this->id, $currentMenu);
    }

    private function getParent($id, $menus)
    {
        if ($id == $menus->parent_id) {
            return 'show';
        } else {
            return $menus->parent_id ? $this->getParent($id, $menus->parent) : '';
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'url', 'model'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
