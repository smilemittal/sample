<?php

namespace Database\Seeders\Custom;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuItem;

class Seeder1680849944 extends Seeder
{
    public function run()
    {
        $menu = Menu::where('name', 'Siteadmin')->firstOrFail();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Form Builder',
            'url'     => '',
            'route'   => 'app.form-builder',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'AdjustmentsVerticalIcon',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 9,
            ])->save();
        }

    }
}