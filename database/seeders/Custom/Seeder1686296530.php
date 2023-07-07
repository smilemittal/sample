<?php

namespace Database\Seeders\Custom;

use Illuminate\Database\Seeder;

class Seeder1686296530 extends Seeder
{
    public function run()
    {
        \DB::statement('update group_forms set company_form_id=ifnull
        ((select (select company_forms.id from company_forms where company_forms.form_id=g.form_id
        and company_forms.company_id =`groups`.company_id)
        FROM `group_forms` as g inner JOIN `groups` on `groups`.id=g.group_id and
         g.id= group_forms.id), 0);');

         \DB::statement('update learning_path_forms set company_form_id=ifnull
         ((select (select company_forms.id from company_forms where company_forms.form_id=l.form_id
         and company_forms.company_id = learning_paths.company_id)
          FROM `learning_path_forms` as l inner JOIN learning_paths on learning_paths.id=l.learning_group_id and
           l.id= learning_path_forms.id), 0);');


    }
}
