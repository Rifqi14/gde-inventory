<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessTripTransportationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_trip_transportations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('business_trip_id');
            $table->string('transportation_type');
            $table->string('description')->nullable();
            $table->integer('price')->nullable();
            $table->string('type');
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
        Schema::dropIfExists('business_trip_transportations');
    }
}