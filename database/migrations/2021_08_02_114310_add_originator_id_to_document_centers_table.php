<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOriginatorIdToDocumentCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_centers', function (Blueprint $table) {
            $table->unsignedBigInteger('originator_id')->nullable();
            $table->foreign('originator_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_centers', function (Blueprint $table) {
            $table->dropForeign(['originator_id']);
            $table->dropColumn('originator_id');
        });
    }
}
