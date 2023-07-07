<?php

namespace Database\Seeders\Custom;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class Seeder1686219209 extends Seeder
{
    public function run()
    {
        $OwnerMenu = Menu::where('name', 'Siteadmin')->firstOrFail();

        $reviewArea = MenuItem::where('menu_id', $OwnerMenu->id)->where('title', 'Review Area')->first();
        if ($reviewArea) {
            $reviewArea->update([
                'title' => 'XME Review Area'
            ]);
        }

    }
}