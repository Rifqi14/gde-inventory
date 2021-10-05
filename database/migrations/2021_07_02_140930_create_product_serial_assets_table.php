<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSerialAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_serial_assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_serial_id');
            $table->string('name')->nullable();
            $table->text('file')->nullable();
            $table->text('type')->nullable();
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
        Schema::dropIfExists('product_serial_assets');
    }
}
