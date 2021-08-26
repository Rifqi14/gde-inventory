<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentCenterDocumentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_center_document_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_center_document_id')->nullable();
            $table->text('document_path')->nullable();
            $table->timestamps();
        });

        Schema::table('document_center_documents', function (Blueprint $table) {
            $table->dropColumn(['document_path']);
        });

        Schema::table('document_center_document_downloads', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->foreign('document_id')->references('id')->on('document_center_document_details')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_center_document_downloads', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->foreign('document_id')->references('id')->on('document_center_documents')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('document_center_documents', function (Blueprint $table) {
            $table->text('document_path')->nullable();
        });

        Schema::dropIfExists('document_center_document_details');
    }
}
