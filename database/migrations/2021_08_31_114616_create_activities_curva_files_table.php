<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesCurvaFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities_curva_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('detail_id')->nullable();
            $table->text('file')->nullable();
            $table->unsignedBigInteger('created_user')->nullable();
            $table->timestamps();

            $table->foreign('detail_id')->references('id')->on('activities_curva_details')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('activities_curva_files');
    }
}
