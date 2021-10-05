<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttentionOutcomingTransmittalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attention_outcoming_transmittals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('outcoming_transmittal_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('outcoming_transmittal_id')->references('id')->on('outcoming_transmittals')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attention_outcoming_transmittals');
    }
}
