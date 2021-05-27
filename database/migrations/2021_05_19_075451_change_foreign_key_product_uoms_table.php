<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeForeignKeyProductUomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_uoms', function (Blueprint $table) {
            $table->dropForeign(['uom_id']);

            $table->foreign('uom_id')->references('id')->on('uoms')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_uoms', function (Blueprint $table) {
            $table->foreign('uom_id')->references('id')->on('uom_categories')->onUpdate('cascade')->onDelete('cascade');

            $table->dropForeign(['uom_id']);
        });
    }
}