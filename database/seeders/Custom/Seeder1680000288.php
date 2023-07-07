<?php

namespace Database\Seeders\Custom;

use Illuminate\Database\Seeder;
use App\Models\PpsSection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Seeder1680000288 extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('pps_sections')->truncate();
        Schema::enableForeignKeyConstraints();
        DB::beginTransaction();

        PpsSection::truncate();
        PpsSection::create([
            'title'         => 'Introduce yourself and your company',
            'sub_heading'   => '<h6><b>Example:</b> “Hey team I’m XXX from XXX”</h6>',
            'display_order' => 1,
            'status'        => 1,
            'is_optional'   => 0,

        ]);

        PpsSection::create([
            'title'         => 'Outline the activity / product / thing / event',
            'sub_heading'   => '<h6><b>Example:</b> “Today we are going to talk about…”</h6>
            <h6>“In this video you will learn…”</h6>',
            'display_order' => 2,
            'status'        => 1,
            'is_optional'   => 0,
        ]);
        PpsSection::create([
            'title'         => 'What is the positive expectation / outcome of the video / reason why',
            'sub_heading'   => '<h6><b>Example:</b> “The reason why I’m making this video is…”</h6><h6>“Because…”</h6>',
            'display_order' => 3,
            'status'        => 1,
            'is_optional'   => 0,
        ]);
        PpsSection::create([
            'title'         => 'Deliver the information / instructions / process / or steps',
            'sub_heading'   => '<h6><b>Example:</b> “Let’s take a look at….”</h6><h6>“Here’s what you need to know…”</h6><h6>“Here we are at (location)…”</h6>',
            'display_order' => 4,
            'status'        => 1,
            'is_optional'   => 0,
        ]);
        PpsSection::create([
            'title'         => 'Recap / Summary',
            'sub_heading'   => '<h4><b>Example:</b> It’s a great habit for all videos, especially
                                long format videos where the viewer may have had a lot of details to consume.
                                </h4><h6>“Let’s recap…”</h6><h6>“Let’s recap some of those critical points
                                 to make sure everyone will be safe…”</h6>',
            'display_order' => 5,
            'status'        => 1,
            'is_optional'   => 1,
        ]);
        PpsSection::create([
            'title'         => 'Further additional support',
            'sub_heading'   => '<h3><b>Example:</b> Sometimes you may have the need to offer further suggestions or
                                 directions to extra support. Eg: an Internal Website, Policy,
                                 Document or another Video to watch. </h3><h6>“Further instructions
                                 can be found…”</h6><h6> “Resources available to support this video/topic
                                 can be found…”</h6>',
            'display_order' => 6,
            'status'        => 1,
            'is_optional'   => 1,
        ]);
        PpsSection::create([
            'title'         => 'Sign off',
            'sub_heading'   => '<h4><b>Example:</b> Address your viewers as you normally would and wrap it up with a
                                 positive and upbeat tone and energy. </h4><h6>“This information is always
                                 here to support you in your role…”</h6><h6>“Have a safe & productive day…
                                 ”</h6><h6>“Keep Kicking Goals…”</h6>',
            'display_order' => 7,
            'status'        => 1,
            'is_optional'   => 0,
        ]);


    }
}
