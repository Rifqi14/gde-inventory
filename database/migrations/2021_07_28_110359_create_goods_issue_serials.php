<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsIssueSerials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_issue_serials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('goods_issue_product_id');
            $table->unsignedBigInteger('serial_id');
            $table->timestamps();

            $table->foreign('goods_issue_product_id')->references('id')->on('goods_issue_products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('serial_id')->references('id')->on('product_serials')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_issue_serials');
    }
}
