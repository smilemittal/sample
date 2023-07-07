<?php
namespace Database\Seeders\Custom;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;

class SuperAdminDevelopMenu extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => 5,
            'title'   => 'Develop',
            'url'     => '',
            'route'   => 'app.develop',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'IdentificationIcon',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 8,
            ])->save();
        }
        //
    }
}
