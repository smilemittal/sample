<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterActionLogsTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('action_logs', function (Blueprint $table) {
            $table->string('subject')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('browser')->nullable();
            
        });
        $query = 'UPDATE action_logs SET subject=\'{user} sent subject "{model}" to {statuses}\' WHERE system_note<>"delete"';
       \DB::statement($query);
       \DB::statement('UPDATE action_logs SET subject=REPLACE(subject, "{statuses}", action_logs.system_note) WHERE system_note <> "delete"');

       \DB::statement('UPDATE action_logs SET subject=\'{user} deleted subject "{model}"\' WHERE system_note="delete"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('action_logs', function (Blueprint $table) {
            $table->dropColumn('subject');
            $table->dropColumn('ip_address');
            $table->dropColumn('browser');
        });
    }
}
