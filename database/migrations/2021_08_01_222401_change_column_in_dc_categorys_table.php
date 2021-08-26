<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnInDcCategorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dc_categorys', function (Blueprint $table) {
            $table->dropColumn(['type', 'name']);

            $table->unsignedBigInteger('menu_id')->nullable();
            $table->unsignedBigInteger('document_type_id')->nullable();

            $table->foreign('menu_id')->references('id')->on('menus')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('document_type_id')->references('id')->on('document_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dc_categorys', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->string('name')->nullable();

            $table->dropForeign(['menu_id']);
            $table->dropForeign(['document_type_id']);
            $table->dropColumn(['menu_id', 'document_type_id']);
        });
    }
}
