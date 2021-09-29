<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrievanceRedressHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grievance_redress_historys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('grievance_id')->nullable();
            $table->text('status')->nullable();
            $table->timestamps();

            $table->foreign('grievance_id')->references('id')->on('grievance_redress')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grievance_redress_historys');
    }
}
