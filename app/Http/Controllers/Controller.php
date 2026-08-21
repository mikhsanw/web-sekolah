<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public string $view;

    public string $code;

    public string $model;

    public string $url;

    public object $help;

    public function __construct()
    {
        $this->help = app('App\Helpers\Helper');
        $menu = $this->help->menu();
        $this->code = $menu->code ?? 'home';
        $this->model = $menu->model ?? 'home';
        $this->url = config('master.app.url.backend') . '/' . ($menu->url ?? 'home');
        $this->view = config('master.app.view.backend') . '.' . $this->code;
    }

    public function javaScript(Request $request, $layout, $page, $file)
    {
        // Cegah view-path injection: hanya izinkan segmen alfanumerik/dash/underscore.
        $safe = fn ($value) => preg_match('/^[A-Za-z0-9_-]+$/', (string) $value) === 1;

        $layoutConfig = config('master.app.view.'.$layout);
        $fileName = Str::before($file, '.js');

        if (! $layoutConfig || ! $safe($page) || ! $safe($fileName)) {
            abort(404);
        }

        $viewPath = "$layoutConfig.$page.$fileName";

        if (! view()->exists($viewPath)) {
            abort(404);
        }

        $data = json_decode($request->getContent(), true) ?? [];
        $data['url'] = $page;

        return response()->view($viewPath, $data)->header('Content-Type', 'application/javascript');
    }
}
