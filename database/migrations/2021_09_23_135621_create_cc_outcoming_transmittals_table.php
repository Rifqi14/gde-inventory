<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCcOutcomingTransmittalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cc_outcoming_transmittals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('outcoming_transmittal_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('outcoming_transmittal_id')->references('id')->on('outcoming_transmittals')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cc_outcoming_transmittals');
    }
}
