<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIndexMinMaxProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('min_max_products', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->foreign('site_id')->references('id')->on('sites')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('min_max_products', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->foreign('site_id')->references('id')->on('sites')->onUpdate('cascade')->onDelete('restrict');
        });
    }
}
