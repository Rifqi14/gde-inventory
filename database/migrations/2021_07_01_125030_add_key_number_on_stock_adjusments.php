<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKeyNumberOnStockAdjusments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_adjustments',function(Blueprint $table){
            $table->string('adjustment_number')->nullable()->change();
            $table->string('key_number')->nullable();
        });

        Schema::table('stock_adjustment_products',function(Blueprint $table){
            $table->unsignedBigInteger('uom_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_adjustments',function(Blueprint $table){
            $table->dropColumn('key_number');
        });

        Schema::table('stock_adjustment_products',function(Blueprint $table){
            $table->dropColumn('uom_id');
        });
    }
}
