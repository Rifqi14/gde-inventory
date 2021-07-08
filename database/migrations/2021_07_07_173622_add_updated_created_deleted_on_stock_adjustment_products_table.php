<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdatedCreatedDeletedOnStockAdjustmentProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_adjustment_products', function (Blueprint $table) {
            $table->text('updated_items')->nullable();
            $table->text('added_items')->nullable();
            $table->text('deleted_items')->nullable();
        });

        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->dropColumn(['updated_items','added_items','deleted_items']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_adjustment_products', function (Blueprint $table) {
            $table->dropColumn(['updated_items','added_items','deleted_items']);
        });

        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->text('updated_items')->nullable();
            $table->text('added_items')->nullable();
            $table->text('deleted_items')->nullable();
        });
    }
}
