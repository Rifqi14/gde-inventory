<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentExternalRevisionFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_external_revision_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('revision_id')->nullable();
            $table->text('document_path')->nullable();
            $table->string('document_name')->nullable();
            $table->float('file_size')->nullable();
            $table->timestamps();

            $table->foreign('revision_id')->references('id')->on('document_external_revisions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_external_revision_files');
    }
}
