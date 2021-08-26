<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePurchasingScheduleNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchasing_schedule_notes', function (Blueprint $table) {
            $table->dropForeign(['purchasing_id']);
            $table->renameColumn('purchasing_id', 'schedule_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchasing_schedule_notes', function (Blueprint $table) {
            $table->renameColumn('schedule_id', 'purchasing_id');
            $table->foreign('purchasing_id')->references('id')->on('purchasings')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
