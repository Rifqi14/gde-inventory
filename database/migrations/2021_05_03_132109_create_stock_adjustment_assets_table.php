<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockAdjustmentAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustment_assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('stock_adjustment_id');
            $table->text('file')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();

            $table->foreign('stock_adjustment_id')->references('id')->on('stock_adjustments')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_adjustment_assets');
    }
}