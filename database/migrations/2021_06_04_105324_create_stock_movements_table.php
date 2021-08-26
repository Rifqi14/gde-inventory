<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('reference')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('uom_id');
            $table->double('uom_qty')->default(0);
            $table->double('qty')->default(0);
            $table->unsignedBigInteger('source_id');
            $table->unsignedBigInteger('destination_id');
            $table->float('price')->nullable();
            $table->float('cost_price')->nullable();
            $table->date('date');
            $table->text('status');
            $table->integer('proceed');
            $table->string('type', 10);
            $table->unsignedBigInteger('creation_user');
            $table->unsignedBigInteger('product_id');
            $table->date('expired_date')->nullable();
            $table->string('production_no', 255)->nullable();
            $table->unsignedBigInteger('product_serial_id');
            $table->unsignedBigInteger('site_id');
            $table->timestamps();

            $table->foreign('uom_id')->references('id')->on('uoms')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('source_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('destination_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('creation_user')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('product_serial_id')->references('id')->on('product_serials')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('site_id')->references('id')->on('sites')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
}
