<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesCurvasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities_curvas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('activity')->nullable();
            $table->date('start_date')->nullable();
            $table->date('finish_date')->nullable();
            $table->text('location')->nullable();
            $table->text('type')->nullable();
            $table->unsignedBigInteger('created_user')->nullable();
            $table->unsignedBigInteger('updated_user')->nullable();
            $table->double('w1')->default(0)->nullable();
            $table->double('w2')->default(0)->nullable();
            $table->double('w3')->default(0)->nullable();
            $table->double('w4')->default(0)->nullable();
            $table->double('w5')->default(0)->nullable();
            $table->timestamp('start_update')->nullable();
            $table->timestamp('last_update')->nullable();
            $table->unsignedBigInteger('sort')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('activities_curvas');
    }
}
