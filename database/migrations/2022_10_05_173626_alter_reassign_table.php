<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterReassignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_groups', function (Blueprint $table) {
            $table->boolean('end_point')->default(false);
            $table->enum('end_type', ['fix', 'custom'])->nullable();
            $table->integer('end_times')->nullable();
            $table->integer('pending_end_times')->nullable();
            $table->date('end_date')->nullable();
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
            //
            $table->dropColumn(['end_point', 'end_type', 'end_times', 'pending_end_times', 'end_date']);
        });
    }
}