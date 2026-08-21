<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Induk seluruh controller halaman publik.
 *
 * Sengaja tidak mewarisi App\Http\Controllers\Controller karena controller
 * tersebut bergantung pada tabel menu backend.
 */
abstract class FrontendController extends BaseController
{
    use ValidatesRequests;

    protected string $viewRoot;

    public function __construct()
    {
        $this->viewRoot = config('master.app.view.frontend');
    }

    /**
     * Render view frontend beserta judul dan breadcrumb halaman.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, string|null>  $breadcrumb  label => url
     */
    protected function tampil(string $view, array $data = [], ?string $judul = null, array $breadcrumb = [])
    {
        return view($this->viewRoot.'.'.$view, array_merge($data, [
            'judulHalaman' => $judul,
            'breadcrumb' => $breadcrumb,
        ]));
    }
}
