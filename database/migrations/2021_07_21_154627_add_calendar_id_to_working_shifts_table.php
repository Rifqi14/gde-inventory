<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCalendarIdToWorkingShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('working_shifts', function (Blueprint $table) {
            $table->unsignedBigInteger('calendar_id')->nullable();
            $table->foreign('calendar_id')->references('id')->on('calendars')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['calendar_id']);
            $table->dropColumn('calendar_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('working_shifts', function (Blueprint $table) {
            $table->dropForeign(['calendar_id']);
            $table->dropColumn('calendar_id');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('calendar_id')->nullable();
            $table->foreign('calendar_id')->references('id')->on('calendars')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
