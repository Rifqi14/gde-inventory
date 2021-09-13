<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStockMovements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_movements', function(Blueprint $table){
            $table->text('status')->nullable()->change();
            $table->unsignedBigInteger('site_id')->nullable()->change();
            
            $table->dropColumn('key_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_movements', function(Blueprint $table){
            $table->text('status')->change();
            $table->unsignedBigInteger('site_id')->change();
        });
    }
}
