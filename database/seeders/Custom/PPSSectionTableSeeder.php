<?php

namespace Database\Seeders\Custom;

use Illuminate\Database\Seeder;
use App\Models\PpsSection;

class PPSSectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PpsSection::create([
            'title'         => __('site.pps_section1_main_heading'),
            'sub_heading'   => __('site.pps_section1_sub_heading'),
            'display_order' => 1,
            'status'        => 1,
            'is_optional'   => 0,

        ]);

        PpsSection::create([
            'title'         => __('site.pps_section2_main_heading'),
            'sub_heading'   => __('site.pps_section2_sub_heading'),
            'display_order' => 2,
            'status'        => 1,
            'is_optional'   => 0,
        ]);
        PpsSection::create([
            'title'         => __('site.pps_section3_main_heading'),
            'sub_heading'   => __('site.pps_section3_sub_heading'),
            'display_order' => 3,
            'status'        => 1,
            'is_optional'   => 0,
        ]);
        PpsSection::create([
            'title'         => __('site.pps_section4_main_heading'),
            'sub_heading'   => __('site.pps_section4_sub_heading'),
            'display_order' => 4,
            'status'        => 1,
            'is_optional'   => 0,
        ]);
        PpsSection::create([
            'title'         => __('site.pps_section5_main_heading'),
            'sub_heading'   => __('site.pps_section5_sub_heading'),
            'display_order' => 5,
            'status'        => 1,
            'is_optional'   => 1,
        ]);
        PpsSection::create([
            'title'         => __('site.pps_section6_main_heading'),
            'sub_heading'   => __('site.pps_section6_sub_heading'),
            'display_order' => 6,
            'status'        => 1,
            'is_optional'   => 1,
        ]);
        PpsSection::create([
            'title'         => __('site.pps_section7_main_heading'),
            'sub_heading'   => __('site.pps_section7_sub_heading'),
            'display_order' => 7,
            'status'        => 1,
            'is_optional'   => 0,
        ]);


        //
    }
}
