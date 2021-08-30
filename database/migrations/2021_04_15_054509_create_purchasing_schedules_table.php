<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasingSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchasing_id')->nullable();
            $table->unsignedBigInteger('adb_id')->nullable();
            $table->text('schedule')->nullable();
            $table->date('date')->nullable();
            $table->date('updated')->nullable();
            $table->text('status')->nullable();
            $table->timestamps();
            $table->foreign('purchasing_id')->references('id')->on('purchasings')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchasing_schedules');
    }
}
