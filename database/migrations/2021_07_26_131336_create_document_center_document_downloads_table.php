<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentCenterDocumentDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_center_document_downloads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_id')->nullable();
            $table->unsignedBigInteger('download_by')->nullable();
            $table->timestamps();

            $table->foreign('document_id')->references('id')->on('document_center_documents')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('download_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_center_document_downloads');
    }
}
