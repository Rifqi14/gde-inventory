<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('adjustment_number');
            $table->date('adjustment_date');
            $table->unsignedBigInteger('site_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->string('status')->nullable();
            $table->unsignedBigInteger('issued_by');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('site_id')->references('id')->on('sites')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('issued_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_adjustments');
    }
}