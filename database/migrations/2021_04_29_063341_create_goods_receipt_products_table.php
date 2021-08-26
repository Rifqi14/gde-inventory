<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsReceiptProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_receipt_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->string('references');
            $table->integer('qty_order');
            $table->integer('qty_receipt');
            $table->unsignedBigInteger('rack_id')->nullable();
            $table->unsignedBigInteger('bin_id')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('rack_id')->references('id')->on('rack_warehouses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('bin_id')->references('id')->on('bin_warehouses')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_receipt_products');
    }
}