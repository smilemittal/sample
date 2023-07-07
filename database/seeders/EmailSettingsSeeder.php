<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailSettingsSeeder extends Seeder
{
    public function run()
    {
        // Settings
        $settingsToSeed = [
            [
                'key' => 'email_header_footer_color',
                'display_name' => 'Email Header Footer Color',
                'value' => null,

            ],
            [
                'key' => 'email_header_image',
                'display_name' => 'Email Header Image',
                'value' => null,

            ],
            [
                'key' => 'email_header_text_size',
                'display_name' => 'Email Header Text Size',
                'value' => '20',

            ],
            [
                'key' => 'email_body_text_size',
                'display_name' => 'Email Body Text Size',
                'value' => '16',

            ],
            [
                'key' => 'email_header_text',
                'display_name' => 'Email Header Text',
                'value' => '',

            ],
            [
                'key' => 'email_footer_text',
                'display_name' => 'Email Footer Text',
                'value' => '© ' . Carbon::now()->year,

            ],
            [
                'key' => 'smtp_settings',
                'display_name' => 'Email SMTP Settings',
                'value' => null,

            ],
        ];
        foreach ($settingsToSeed as $key => $value) {

            // $setting = DB::table('settings')->where('name', $value['key'])->first();
            // if (!$setting) {
            $setting = DB::table('settings')->insertGetId(
                [
                    'name'          => $value['key'],
                    'label' => $value['display_name'],
                    'value'        => $value['value'],
                ]
            );
            // }
        }
    }
}
