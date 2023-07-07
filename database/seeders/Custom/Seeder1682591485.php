<?php

namespace Database\Seeders\Custom;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class Seeder1682591485 extends Seeder
{
    public function run()
    {
        $menu = Menu::where('name', 'Siteadmin')->firstOrFail();

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Subscription Plans',
            'url'     => '',
            'route'   => 'admin.plans.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'CreditCardIcon',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 10,
            ])->save();
        }

        $memberMenu = Menu::where('name', 'member')->firstOrFail();
        $memberMenuItem = MenuItem::firstOrNew([
            'menu_id' => $memberMenu->id,
            'title'   => 'Overview',
            'url'     => '',
            'route'   => 'xme.overview',
        ]);
        if (!$memberMenuItem->exists) {
            $memberMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'fas fa-home HomeIcon',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 0,
            ])->save();
        }


    }
}
