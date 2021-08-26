<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignToDocumentCenterDocumentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_center_document_details', function (Blueprint $table) {
            $table->foreign('document_center_document_id')->references('id')->on('document_center_documents')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_center_document_details', function (Blueprint $table) {
            $table->dropForeign(['document_center_document_id']);
        });
    }
}
