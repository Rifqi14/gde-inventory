<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasingScheduleNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_schedule_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchasing_id')->nullable();
            $table->text('notes')->nullable();
            $table->text('file')->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('purchasing_schedule_notes');
    }
}
