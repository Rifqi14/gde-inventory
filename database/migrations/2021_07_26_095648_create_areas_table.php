<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name')->nullable();
            $table->unsignedBigInteger('create_user')->nullable();
            $table->unsignedBigInteger('updated_user')->nullable();
            $table->unsignedBigInteger('site_id')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->foreign('create_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areas');
    }
}
