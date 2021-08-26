<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBusinessTripIdToTableBusinessTripOthers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_trip_others', function (Blueprint $table) {
            $table->unsignedBigInteger('business_trip_id');

            $table->foreign('business_trip_id')->references('id')->on('business_trips')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_trip_others', function (Blueprint $table) {
            $table->dropForeign(['business_trip_id']);
            $table->dropColumn('business_trip_id');
        });
    }
}
