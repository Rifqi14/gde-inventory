<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesCurvaDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities_curva_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('activities_id')->nullable();
            $table->double('month')->default(0)->nullable();
            $table->double('year')->default(0)->nullable();
            $table->double('progress')->default(0)->nullable();
            $table->text('file')->nullable();
            $table->unsignedBigInteger('created_user')->nullable();
            $table->text('type')->nullable();
            $table->date('date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('activities_id')->references('id')->on('activities_curvas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities_curva_details');
    }
}
