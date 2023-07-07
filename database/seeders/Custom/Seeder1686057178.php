<?php

namespace Database\Seeders\Custom;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class Seeder1686057178 extends Seeder
{
    public function run()
    {

        $settingsToSeed = [
            [
                'key' => 'email_header_footer_color',
                'display_name' => 'Email Header Footer Color',
                'value' => '#000000',

            ],

        ];
        foreach ($settingsToSeed as $value) {
            Setting::updateOrCreate(
                ['key' => $value['key']],
                [
                    'key' => $value['key'],
                    'display_name' => $value['display_name'],
                    'value'        => $value['value'],
                ]
            );
        }
    }
}
