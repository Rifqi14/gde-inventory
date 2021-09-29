<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSafeguardIncidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('safeguard_incidents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('reporter')->nullable();
            $table->text('subject')->nullable();
            $table->text('type')->nullable();
            $table->double('loss_time')->default(0);
            $table->text('unit')->nullable();
            $table->date('date')->nullable();
            $table->text('time')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->text('status')->nullable();
            $table->unsignedBigInteger('created_user')->nullable();
            $table->unsignedBigInteger('updated_user')->nullable();
            $table->text('number')->nullable();
            $table->text('remarks')->nullable();
            $table->text('comment')->nullable();
            $table->text('attachment')->nullable();
            $table->timestamps();

            $table->foreign('area_id')->references('id')->on('areas')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('created_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('updated_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('safeguard_incidents');
    }
}
