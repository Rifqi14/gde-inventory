<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTableStockMovements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_movements',function(Blueprint $table){            
            $table->unsignedBigInteger('source_id')->nullable()->change();
            $table->unsignedBigInteger('product_serial_id')->nullable()->change();
            $table->integer('proceed')->nullable()->change();
            $table->text('key_number')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_movements',function(Blueprint $table){            
            $table->dropColumn('key_number');
        });
    }
}
