<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteOnProductConsumables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_consumables',function(Blueprint $table){
            $table->string('reject_reason')->nullable()->change();
            $table->string('key_number')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_consumables',function(Blueprint $table){
            $table->dropColumn('key_number');            
        });
    }
}
