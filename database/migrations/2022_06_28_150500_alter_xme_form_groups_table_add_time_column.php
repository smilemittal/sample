<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterXmeFormGroupsTableAddTimeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_groups', function (Blueprint $table) {
            $table->string('week_reassign_day')->nullable();
            $table->string('month_reassign_day')->nullable();
            $table->time('reassign_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_groups', function (Blueprint $table) {
            $table->dropColumn('week_reassign_day');
            $table->dropColumn('month_reassign_day');
            $table->dropColumn('reassign_time');
        });
    }
}
