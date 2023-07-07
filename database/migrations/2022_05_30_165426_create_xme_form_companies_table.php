<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXmeFormCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('form_id');
            $table->unsignedInteger('company_id');
            $table->timestamps();
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });


        \DB::statement('INSERT INTO form_companies (form_id, company_id) SELECT id, company_id FROM forms');


        // Schema::table('forms', function (Blueprint $table) {
        //     $table->dropColumn('company_id');
        // });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('forms', function (Blueprint $table) {
        //     $table->unsignedInteger('company_id')->after('id');
        // });

        // \DB::statement('UPDATE forms SET forms.company_id=(SELECT form_companies.company_id FROM form_companies WHERE forms.id=form_companies.form_id)');

        Schema::dropIfExists('form_companies');

    }
}
