<?php

namespace App\Http\Controllers\Frontend;

class KontakController extends FrontendController
{
    public function index()
    {
        return $this->tampil('kontak.index', [], 'Kontak Kami', ['Kontak' => null]);
    }
}
