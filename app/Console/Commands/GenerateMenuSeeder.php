<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Menu;
use Illuminate\Support\Facades\File;

class GenerateMenuSeeder extends Command
{
    protected $signature = 'menu:generate-seeder';
    protected $description = 'Generate Menu Seeder from database';

    public function handle()
    {
        $menus = Menu::orderBy('sort')->get();

        $tree = $this->buildTree($menus);

        $json = json_encode($tree, JSON_PRETTY_PRINT);

        $content = "<?php

        namespace Database\Seeders;

        use Illuminate\Database\Seeder;
        use App\Models\Menu;

        class MenuSeeder extends Seeder
        {
            public function run()
            {
                \$menus = '$json';
                \$data = json_decode(\$menus, true);

                foreach (\$data as \$item) {
                    \$this->storeMenu(\$item);
                }
            }

            private function storeMenu(\$item, \$parentId = null)
            {
                \$children = \$item['children'] ?? [];
                unset(\$item['children']);

                \$menu = Menu::updateOrCreate(
                    ['code' => \$item['code']],
                    array_merge(\$item, ['parent_id' => \$parentId])
                );

                foreach (\$children as \$child) {
                    \$this->storeMenu(\$child, \$menu->id);
                }
            }
        }
        ";

        File::put(database_path('seeders/MenuSeeder.php'), $content);

        $this->info('MenuSeeder generated successfully!');
    }

    private function buildTree($menus, $parentId = null)
    {
        $branch = [];

        foreach ($menus as $menu) {
            if ($menu->parent_id == $parentId) {
                $children = $this->buildTree($menus, $menu->id);

                $item = $menu->toArray();
                $item['children'] = $children;

                $branch[] = $item;
            }
        }

        return $branch;
    }
}
