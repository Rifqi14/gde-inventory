<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBorrowerRequestVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrower_request_vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('request_vehicle_id');
            $table->unsignedBigInteger('employee_id');
            $table->timestamps();

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
        Schema::dropIfExists('borrower_request_vehicles');
    }
}