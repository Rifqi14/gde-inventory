<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUomAndQtyOnProductSerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_serials', function (Blueprint $table) {
            $table->unsignedBigInteger('uom_id');
            $table->double('uom_qty')->default(0);
            $table->double('qty')->default(0);
            $table->text('position')->nullable();
            $table->foreign('uom_id')->references('id')->on('uoms')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_serials', function (Blueprint $table) {
            $table->dropForeign(['uom_id']);
            $table->dropColumn(['uom_id', 'uom_qty', 'qty', 'position']);
        });
    }
}
