<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessTripLodgingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_trip_lodgings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('business_trip_id');
            $table->string('place')->nullable();
            $table->integer('price')->nullable();
            $table->integer('night')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('business_trip_lodgings');
    }
}