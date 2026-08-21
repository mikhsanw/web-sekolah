<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LayoutController extends Controller
{
    public function index(Request $request)
    {
        $menus = \App\Models\Menu::where('active', true)->whereNull('parent_id')->orderBy('sort', 'asc')->with('childrenRecursive')->get();

        $menus = $this->transformMenu($menus);

        $data = [
            'user' => $request->user(),
            // 'menus' => $menus,
            'menus' => $menus,
        ];
        return response()->json([
            'status' => 'success',
            'message' => 'Layout API loaded successfully.',
            'data' => $data,
        ]);
    }

    private function transformMenu($menus)
    {
        return $menus
            ->map(function ($menu) {
                return [
                    'id' => $menu->id,
                    'name' => $menu->title,
                    'path' => $menu->url,
                    'icon' => 'GridIcon',
                    'group' => $menu->type,
                    'children_recursive' => $menu->childrenRecursive->isNotEmpty() ? $this->transformMenu($menu->childrenRecursive) : [],
                ];
            })
            ->values();
    }
}
