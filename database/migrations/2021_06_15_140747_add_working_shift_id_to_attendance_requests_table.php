<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWorkingShiftIdToAttendanceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('working_shift_id')->nullable();
            $table->foreign('working_shift_id')->references('id')->on('working_shifts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_requests', function (Blueprint $table) {
            $table->dropForeign(['working_shift_id']);
            $table->dropColumn('working_shift_id');
        });
    }
}