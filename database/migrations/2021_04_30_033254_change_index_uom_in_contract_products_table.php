<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIndexUomInContractProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_products', function (Blueprint $table) {
            $table->dropForeign(['uom_id']);
            $table->foreign('uom_id')->references('id')->on('uom_categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_products', function (Blueprint $table) {
            $table->dropForeign(['uom_id']);
            $table->foreign('uom_id')->references('id')->on('uom_categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
