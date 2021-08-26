<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogRevisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_revises', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('route_menu');
            $table->unsignedBigInteger('data_id');
            $table->string('revise_number');
            $table->text('revise_reason');
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
        Schema::dropIfExists('log_revises');
    }
}