<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGoodsIssueProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_issue_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('goods_issue_id');
            $table->unsignedBigInteger('reference_id');
            $table->unsignedBigInteger('product_id');  
            $table->unsignedBigInteger('uom_id');
            $table->integer('qty_request');
            $table->integer('qty_receive');
            $table->unsignedBigInteger('rack_id')->nullable();
            $table->unsignedBigInteger('bin_id')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();

            $table->foreign('goods_issue_id')->references('id')->on('goods_issues')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('uom_id')->references('id')->on('uoms')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('table_goods_issue_products');
    }
}
