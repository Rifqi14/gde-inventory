<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductConsumablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_consumables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('consumable_number')->nullable();
            $table->unsignedBigInteger('site_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('issued_by');
            $table->date('consumable_date');
            $table->string('status');
            $table->text('description');
            $table->text('reject_reason');
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
        Schema::dropIfExists('product_consumables');
    }
}