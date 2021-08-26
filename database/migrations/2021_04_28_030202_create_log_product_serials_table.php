<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogProductSerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_product_serials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_serial_id');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('product_serial_id')->references('id')->on('product_serials')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_product_serials');
    }
}