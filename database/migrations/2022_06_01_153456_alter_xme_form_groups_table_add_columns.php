<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterXmeFormGroupsTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_groups', function (Blueprint $table) {
            $table->boolean('is_reassign')->default(false);
            $table->enum('reassign_interval', ['monthly', 'weekly', 'custom'])->nullable();
            $table->integer('custom_interval')->default(0);
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
            $table->dropColumn('is_reassign');
            $table->dropColumn('reassign_interval');
            $table->dropColumn('custom_interval');
        });
    }
}
