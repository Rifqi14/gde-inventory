<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRackWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rack_warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('warehouse_id');
            $table->string('name', 50);
            $table->timestamps();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rack_warehouses');
    }
}
