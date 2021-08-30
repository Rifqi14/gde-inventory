<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessTripDeclarationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_trip_declarations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('declaration_number')->nullable();
            $table->string('index_number')->nullable();
            $table->unsignedBigInteger('business_trip_id')->nullable();
            $table->unsignedBigInteger('declaration_by')->nullable();
            $table->timestamps();

            $table->foreign('business_trip_id')->references('id')->on('business_trips')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('declaration_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_trip_declarations');
    }
}