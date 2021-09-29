<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeForeignColumnCategoryDocumentExternalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_document_externals', function (Blueprint $table) {
            $table->dropForeign(['document_type_id']);

            $table->dropColumn(['document_type_id']);

            $table->unsignedBigInteger('discipline_code_id')->nullable();
            $table->foreign('discipline_code_id')->references('id')->on('document_external_discipline_codes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_document_externals', function (Blueprint $table) {
            $table->dropForeign(['discipline_code_id']);
            $table->dropColumn(['discipline_code_id']);

            $table->unsignedBigInteger('document_type_id')->nullable();
            $table->foreign('document_type_id')->references('id')->on('document_external_document_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
