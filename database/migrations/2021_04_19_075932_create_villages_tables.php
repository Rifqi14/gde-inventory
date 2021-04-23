<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVillagesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('villages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('district_id');
            $table->string('name');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('villages');
    }
}
