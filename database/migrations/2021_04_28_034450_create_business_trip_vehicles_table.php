<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessTripVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_trip_vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('business_trip_id');
            $table->unsignedBigInteger('request_vehicle_id');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('business_trip_id')->references('id')->on('business_trips')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('request_vehicle_id')->references('id')->on('request_vehicles')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_trip_vehicles');
    }
}