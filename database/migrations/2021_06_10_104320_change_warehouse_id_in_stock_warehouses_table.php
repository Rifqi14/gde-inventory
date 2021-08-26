<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeWarehouseIdInStockWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_warehouses', function (Blueprint $table) {
            $table->dropForeign(['warehose_id']);
            $table->renameColumn('warehose_id', 'warehouse_id');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_warehouses', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->renameColumn('warehouse_id', 'warehose_id');
            $table->foreign('warehose_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('restrict');
        });
    }
}
