<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXmeTrainingHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('form_id');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('user_id');
            $table->dateTime('completed_date')->nullable();
            $table->dateTime('assigned_date')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->enum('status', ['completed', 'skipped', 'pending'])->default('pending');
            $table->timestamps();
        });

        \DB::statement('INSERT INTO `training_history` (`group_id`, `user_id`,`form_id`, `assigned_date`) SELECT `form_groups`.`group_id`, `user_groups`.`user_id`, `forms`.`id` as `form_id`, `form_groups`.`created_at` FROM `forms` INNER JOIN `form_groups` ON `forms`.`id` = `form_groups`.`form_id` INNER JOIN `user_groups` ON `user_groups`.`group_id`=`form_groups`.`group_id`;');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_history');
    }
}
